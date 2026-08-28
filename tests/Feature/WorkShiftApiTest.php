<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkShiftApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_work_shifts(): void
    {
        $this->getJson('/api/work-shifts')->assertUnauthorized();
    }

    public function test_authenticated_user_can_manage_work_shifts(): void
    {
        $this->actingAs(User::factory()->create());

        $this->getJson('/api/work-shifts')
            ->assertOk()
            ->assertJsonCount(18, 'data')
            ->assertJsonPath('data.0.id', 24);

        $this->postJson('/api/work-shifts', [
            'id' => 50,
            'start_time' => '15:00',
            'end_time' => '23:30',
        ])->assertCreated()->assertJsonPath('data.id', 50);

        $this->putJson('/api/work-shifts/50', [
            'id' => 50,
            'start_time' => '15:30',
            'end_time' => '23:45',
        ])->assertOk()->assertJsonPath('data.start_time', '15:30');

        $this->deleteJson('/api/work-shifts/50')->assertNoContent();
        $this->assertDatabaseMissing('work_shifts', ['id' => 50]);
    }

    public function test_end_time_must_be_after_start_time(): void
    {
        $this->actingAs(User::factory()->create())
            ->postJson('/api/work-shifts', ['id' => 50, 'start_time' => '10:00', 'end_time' => '09:00'])
            ->assertUnprocessable();
    }
}
