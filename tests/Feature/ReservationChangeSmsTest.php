<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Reservation;
use App\Models\SmsSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReservationChangeSmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_changing_therapist_sends_notification_sms(): void
    {
        $this->actingAs(User::factory()->create());
        $firstEmployee = $this->employee('Ayşe', 'Demir');
        $secondEmployee = $this->employee('Elif', 'Aydın');
        $reservation = $this->reservation($firstEmployee);
        $this->smsSettings();

        Http::fake(['sms.verimor.com.tr/*' => Http::response([
            'campaign_id' => 123456,
            'status' => '0',
        ])]);

        $this->putJson("/api/reservations/{$reservation->id}", $this->payload($secondEmployee))
            ->assertOk()
            ->assertJsonPath('sms_notification.status', 'submitted');

        Http::assertSent(fn ($request) => $request['messages'][0]['dest'] === '905435480122'
            && ! isset($request['source_addr'])
            && str_contains($request['messages'][0]['msg'], 'Ayşe Demir -> Elif Aydın')
        );
        $this->assertDatabaseHas('sms_messages', [
            'destination' => '905435480122',
            'status' => 'submitted',
        ]);
    }

    public function test_changing_time_sends_notification_sms(): void
    {
        $this->actingAs(User::factory()->create());
        $employee = $this->employee('Ayşe', 'Demir');
        $reservation = $this->reservation($employee);
        $this->smsSettings();
        Http::fake(['sms.verimor.com.tr/*' => Http::response(['campaign_id' => 654321, 'status' => '0'])]);

        $payload = $this->payload($employee);
        $payload['start_time'] = '10:30';
        $payload['end_time'] = '11:30';

        $this->putJson("/api/reservations/{$reservation->id}", $payload)
            ->assertOk()
            ->assertJsonPath('sms_notification.status', 'submitted');

        Http::assertSent(fn ($request) => str_contains($request['messages'][0]['msg'], '09:00-10:00 -> 10:30-11:30'));
    }

    public function test_other_reservation_changes_do_not_send_sms(): void
    {
        $this->actingAs(User::factory()->create());
        $employee = $this->employee('Ayşe', 'Demir');
        $reservation = $this->reservation($employee);
        $this->smsSettings();
        Http::fake();

        $payload = $this->payload($employee);
        $payload['notes'] = 'Not değişti';

        $this->putJson("/api/reservations/{$reservation->id}", $payload)
            ->assertOk()
            ->assertJsonPath('sms_notification.status', 'not_triggered');

        Http::assertNothingSent();
    }

    private function employee(string $firstName, string $lastName): Employee
    {
        return Employee::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'status' => 'aktif',
        ]);
    }

    private function reservation(Employee $employee): Reservation
    {
        return Reservation::create([
            'employee_id' => $employee->id,
            'guest_name' => 'Deniz Yılmaz',
            'phone' => '0532 000 00 01',
            'service_name' => 'Klasik Masaj',
            'reservation_date' => '2026-09-03',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'planned',
        ]);
    }

    private function payload(Employee $employee): array
    {
        return [
            'employee_id' => $employee->id,
            'guest_name' => 'Deniz Yılmaz',
            'phone' => '0532 000 00 01',
            'service_name' => 'Klasik Masaj',
            'reservation_date' => '2026-09-03',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'planned',
            'notes' => null,
        ];
    }

    private function smsSettings(): void
    {
        SmsSetting::create([
            'provider' => 'verimor',
            'username' => 'spa@example.com',
            'password' => 'secret-api-password',
            'source_addr' => null,
            'active' => true,
        ]);
    }
}
