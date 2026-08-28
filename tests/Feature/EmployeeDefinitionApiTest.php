<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeDefinitionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_occupations_and_work_groups_can_be_managed_and_assigned(): void
    {
        $this->actingAs(User::factory()->create());

        $occupationId = $this->postJson('/api/occupations', ['name' => 'Masaj Terapisti'])
            ->assertCreated()->json('data.id');
        $workGroupId = $this->postJson('/api/work-groups', ['name' => 'Spa Ekibi'])
            ->assertCreated()->json('data.id');

        $this->postJson('/api/employees', [
            'first_name' => 'AYŞE',
            'last_name' => 'DEMİR',
            'occupation_id' => $occupationId,
            'work_group_id' => $workGroupId,
            'status' => 'aktif',
        ])->assertCreated()
            ->assertJsonPath('data.occupation.name', 'Masaj Terapisti')
            ->assertJsonPath('data.work_group.name', 'Spa Ekibi');

        $this->putJson("/api/occupations/{$occupationId}", ['name' => 'Uzman Terapist'])->assertOk();
        $this->deleteJson("/api/work-groups/{$workGroupId}")->assertNoContent();
        $this->assertDatabaseHas('employees', ['first_name' => 'AYŞE', 'work_group_id' => null]);
    }
}
