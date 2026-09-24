<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationStatusApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_status_can_be_changed_directly(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::create([
            'guest_name' => 'TEST MİSAFİR',
            'service_name' => 'Masaj',
            'reservation_date' => '2026-09-24',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'planned',
        ]);

        $this->actingAs($user)
            ->patchJson("/api/reservations/{$reservation->id}/status", ['status' => 'completed'])
            ->assertOk()
            ->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'completed']);
    }
}
