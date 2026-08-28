<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessHourApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_business_hours(): void
    {
        $this->getJson('/api/business-hours')->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_and_update_the_week(): void
    {
        $this->actingAs(User::factory()->create());

        $this->getJson('/api/business-hours')
            ->assertOk()
            ->assertJsonCount(7, 'data')
            ->assertJsonPath('data.0.day_of_week', 1);

        $hours = collect(range(1, 7))->map(fn (int $day) => [
            'day_of_week' => $day,
            'opening_time' => $day === 7 ? null : '08:30',
            'closing_time' => $day === 7 ? null : '22:30',
            'is_closed' => $day === 7,
        ])->all();

        $this->putJson('/api/business-hours', ['hours' => $hours])
            ->assertOk()
            ->assertJsonCount(7, 'data')
            ->assertJsonPath('data.6.is_closed', true);

        $this->assertDatabaseHas('business_hours', [
            'day_of_week' => 7,
            'opening_time' => null,
            'closing_time' => null,
            'is_closed' => true,
        ]);
    }

    public function test_closing_time_must_be_after_opening_time(): void
    {
        $this->actingAs(User::factory()->create());

        $hours = collect(range(1, 7))->map(fn (int $day) => [
            'day_of_week' => $day,
            'opening_time' => '22:00',
            'closing_time' => '09:00',
            'is_closed' => false,
        ])->all();

        $this->putJson('/api/business-hours', ['hours' => $hours])->assertUnprocessable();
    }
}
