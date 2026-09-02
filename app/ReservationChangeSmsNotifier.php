<?php

namespace App;

use App\Models\Employee;
use App\Models\Reservation;
use App\Models\SmsMessage;
use App\Models\SmsSetting;
use Throwable;

class ReservationChangeSmsNotifier
{
    public function __construct(private readonly VerimorSmsService $smsService) {}

    public function notify(Reservation $reservation, array $original): array
    {
        if (! $this->hasRelevantChange($reservation, $original)) {
            return ['status' => 'not_triggered'];
        }

        $settings = SmsSetting::query()->first();
        if (! $settings?->active || ! $settings->isConfigured()) {
            return ['status' => 'skipped', 'reason' => 'sms_not_configured'];
        }

        $destination = (string) config('services.verimor.reservation_change_destination');
        $message = $this->message($reservation, $original);

        try {
            $normalizedDestination = $this->smsService->normalizeDestination($destination);
        } catch (Throwable $exception) {
            report($exception);

            return ['status' => 'failed', 'reason' => 'invalid_destination'];
        }

        $record = SmsMessage::create([
            'recipient_name' => 'Rezervasyon Değişiklik Bildirimi',
            'destination' => $normalizedDestination,
            'message' => $message,
            'status' => 'pending',
        ]);

        try {
            $result = $this->smsService->send($settings, $normalizedDestination, $message);
            $record->update([
                'campaign_id' => isset($result['campaign_id']) ? (string) $result['campaign_id'] : null,
                'status' => 'submitted',
                'provider_response' => $result,
                'sent_at' => now(),
            ]);

            return ['status' => 'submitted', 'message_id' => $record->id];
        } catch (Throwable $exception) {
            report($exception);
            $record->update([
                'status' => 'failed',
                'provider_response' => ['error' => $exception->getMessage()],
            ]);

            return ['status' => 'failed', 'message_id' => $record->id];
        }
    }

    private function hasRelevantChange(Reservation $reservation, array $original): bool
    {
        return (int) ($original['employee_id'] ?? 0) !== (int) ($reservation->employee_id ?? 0)
            || $this->time($original['start_time'] ?? null) !== $this->time($reservation->start_time)
            || $this->time($original['end_time'] ?? null) !== $this->time($reservation->end_time);
    }

    private function message(Reservation $reservation, array $original): string
    {
        $oldEmployee = Employee::find($original['employee_id'] ?? null);
        $newEmployee = $reservation->employee;
        $date = $reservation->reservation_date?->format('d.m.Y') ?? (string) $reservation->reservation_date;

        return sprintf(
            'Rezervasyon güncellendi. Misafir: %s. Tarih: %s. Terapist: %s -> %s. Saat: %s-%s -> %s-%s.',
            $reservation->guest_name,
            $date,
            $this->employeeName($oldEmployee),
            $this->employeeName($newEmployee),
            $this->time($original['start_time'] ?? null),
            $this->time($original['end_time'] ?? null),
            $this->time($reservation->start_time),
            $this->time($reservation->end_time),
        );
    }

    private function employeeName(?Employee $employee): string
    {
        return $employee ? trim($employee->first_name.' '.$employee->last_name) : 'Atanmadı';
    }

    private function time(mixed $value): string
    {
        return substr((string) $value, 0, 5);
    }
}
