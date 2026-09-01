<?php

namespace App;

use App\Models\SmsSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class VerimorSmsService
{
    public function send(SmsSetting $settings, string $destination, string $message, array $options = []): array
    {
        $payload = [
            'username' => $settings->username,
            'password' => $settings->password,
            'messages' => [[
                'dest' => $this->normalizeDestination($destination),
                'msg' => $message,
            ]],
        ];

        if (filled($settings->source_addr)) {
            $payload['source_addr'] = $settings->source_addr;
        }
        if (! empty($options['is_commercial'])) {
            $payload['is_commercial'] = true;
            $payload['iys_recipient_type'] = $options['iys_recipient_type'] ?? 'BIREYSEL';
        }

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(20)
            ->post(config('services.verimor.endpoint', 'https://sms.verimor.com.tr/v2/send.json'), $payload);

        $response->throw();
        $result = $response->json();
        if (! is_array($result)) {
            $result = ['campaign_id' => trim($response->body()), 'status' => '0'];
        }
        if (isset($result['status']) && (string) $result['status'] !== '0') {
            throw new RuntimeException('Verimor SMS gönderimini kabul etmedi: '.json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return $result;
    }

    public function normalizeDestination(string $destination): string
    {
        $digits = preg_replace('/\D+/', '', $destination);
        if (strlen($digits) === 11 && str_starts_with($digits, '05')) {
            $digits = '9'.$digits;
        } elseif (strlen($digits) === 10 && str_starts_with($digits, '5')) {
            $digits = '90'.$digits;
        }

        if (! preg_match('/^905\d{9}$/', $digits)) {
            throw ValidationException::withMessages([
                'destination' => 'Telefon numarası 05XX XXX XX XX biçiminde geçerli bir Türkiye mobil numarası olmalıdır.',
            ]);
        }

        return $digits;
    }
}
