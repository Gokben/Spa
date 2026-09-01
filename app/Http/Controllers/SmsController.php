<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use App\Models\SmsSetting;
use App\VerimorSmsService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class SmsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => [
            'settings' => $this->settingsPayload(SmsSetting::query()->first()),
            'messages' => SmsMessage::query()->latest()->limit(30)->get(),
        ]]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => ['nullable', 'string', 'max:190'],
            'password' => ['nullable', 'string', 'max:500'],
            'source_addr' => ['nullable', 'string', 'max:20'],
            'active' => ['required', 'boolean'],
        ]);
        $settings = SmsSetting::query()->first() ?? new SmsSetting(['provider' => 'verimor']);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }
        $settings->fill($data);
        if ($settings->active && ! $settings->isConfigured()) {
            throw ValidationException::withMessages([
                'username' => 'SMS entegrasyonunu etkinleştirmek için kullanıcı adı ve API şifresi gereklidir.',
            ]);
        }
        $settings->save();

        return response()->json(['data' => $this->settingsPayload($settings)]);
    }

    public function send(Request $request, VerimorSmsService $service): JsonResponse
    {
        $data = $request->validate([
            'destination' => ['required', 'string', 'max:40'],
            'recipient_name' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:1071'],
            'is_commercial' => ['sometimes', 'boolean'],
            'iys_recipient_type' => ['nullable', Rule::in(['BIREYSEL', 'TACIR'])],
        ]);
        $settings = SmsSetting::query()->first();
        if (! $settings?->active || ! $settings->isConfigured()) {
            throw ValidationException::withMessages([
                'destination' => 'Önce Kurulum > SMS Ayarları bölümünden Verimor hesabını etkinleştirin.',
            ]);
        }
        $destination = $service->normalizeDestination($data['destination']);
        $record = SmsMessage::create([
            'recipient_name' => $data['recipient_name'] ?? null,
            'destination' => $destination,
            'message' => $data['message'],
            'status' => 'pending',
            'sent_by' => $request->user()?->id,
        ]);

        try {
            $result = $service->send($settings, $destination, $data['message'], $data);
            $record->update([
                'campaign_id' => isset($result['campaign_id']) ? (string) $result['campaign_id'] : null,
                'status' => 'submitted',
                'provider_response' => $result,
                'sent_at' => now(),
            ]);

            return response()->json(['data' => $record->fresh()], 201);
        } catch (ValidationException $exception) {
            $record->delete();
            throw $exception;
        } catch (ConnectionException|RequestException $exception) {
            $record->update(['status' => 'failed', 'provider_response' => ['error' => $exception->getMessage()]]);

            return response()->json(['message' => 'Verimor SMS servisine ulaşılamadı.', 'data' => $record->fresh()], 502);
        } catch (Throwable $exception) {
            report($exception);
            $record->update(['status' => 'failed', 'provider_response' => ['error' => $exception->getMessage()]]);

            return response()->json(['message' => $exception->getMessage(), 'data' => $record->fresh()], 422);
        }
    }

    private function settingsPayload(?SmsSetting $settings): array
    {
        return [
            'provider' => 'verimor',
            'username' => $settings?->username,
            'source_addr' => $settings?->source_addr,
            'active' => (bool) $settings?->active,
            'has_password' => $settings?->isConfigured() ?? false,
        ];
    }
}
