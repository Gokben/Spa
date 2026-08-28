<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeScheduleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_weekly_schedule(): void
    {
        $this->getJson('/api/employee-schedules?week_start=2026-08-24')->assertUnauthorized();
    }

    public function test_weekly_schedule_returns_active_employees_shifts_and_saves_assignments(): void
    {
        $this->actingAs(User::factory()->create());
        $active = Employee::create(['first_name' => 'KAAN', 'last_name' => 'KARAKAYA', 'status' => 'aktif']);
        Employee::create(['first_name' => 'ESKİ', 'last_name' => 'PERSONEL', 'status' => 'ayrıldı']);

        $this->getJson('/api/employee-schedules?week_start=2026-08-26')
            ->assertOk()
            ->assertJsonPath('data.week_start', '2026-08-24')
            ->assertJsonCount(1, 'data.employees')
            ->assertJsonCount(18, 'data.work_shifts');

        $this->putJson('/api/employee-schedules', [
            'week_start' => '2026-08-24',
            'assignments' => [
                ['employee_id' => $active->id, 'work_date' => '2026-08-24', 'work_shift_id' => 27, 'status' => null],
                ['employee_id' => $active->id, 'work_date' => '2026-08-25', 'work_shift_id' => null, 'status' => 'off'],
            ],
        ])->assertOk()->assertJsonCount(2, 'data.assignments');

        $this->assertDatabaseHas('employee_schedules', ['employee_id' => $active->id, 'work_date' => '2026-08-24', 'work_shift_id' => 27]);
        $this->assertDatabaseHas('employee_schedules', ['employee_id' => $active->id, 'work_date' => '2026-08-25', 'status' => 'off']);
    }

    public function test_assignment_date_must_be_in_selected_week(): void
    {
        $this->actingAs(User::factory()->create());
        $employee = Employee::create(['first_name' => 'ASU', 'last_name' => 'MARAT', 'status' => 'aktif']);

        $this->putJson('/api/employee-schedules', [
            'week_start' => '2026-08-24',
            'assignments' => [['employee_id' => $employee->id, 'work_date' => '2026-09-01', 'work_shift_id' => 1, 'status' => null]],
        ])->assertUnprocessable();
    }
}
