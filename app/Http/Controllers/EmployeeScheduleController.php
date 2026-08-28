<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\BusinessHour;
use App\Models\WorkShift;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate(['week_start' => ['required', 'date_format:Y-m-d']]);
        $start = CarbonImmutable::parse($validated['week_start'])->startOfWeek();
        $end = $start->addDays(6);

        return response()->json(['data' => [
            'week_start' => $start->toDateString(),
            'employees' => Employee::query()->where('status', 'aktif')->orderBy('first_name')->orderBy('last_name')->get(),
            'work_shifts' => WorkShift::query()->orderBy('sort_order')->get(),
            'business_hours' => BusinessHour::query()->orderBy('day_of_week')->get(),
            'assignments' => EmployeeSchedule::query()
                ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('work_date')->get(),
        ]]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'week_start' => ['required', 'date_format:Y-m-d'],
            'assignments' => ['required', 'array'],
            'assignments.*.employee_id' => ['required', 'integer', Rule::exists('employees', 'id')->where('status', 'aktif')],
            'assignments.*.work_date' => ['required', 'date_format:Y-m-d'],
            'assignments.*.work_shift_id' => ['nullable', 'integer', 'exists:work_shifts,id'],
            'assignments.*.status' => ['nullable', Rule::in(['off', 'izin', 'raporlu'])],
        ]);

        $start = CarbonImmutable::parse($validated['week_start'])->startOfWeek();
        $end = $start->addDays(6);
        $businessHours = BusinessHour::query()->get()->keyBy('day_of_week');
        $workShifts = WorkShift::query()->get()->keyBy('id');

        foreach ($validated['assignments'] as $assignment) {
            $date = CarbonImmutable::parse($assignment['work_date']);
            abort_unless($date->betweenIncluded($start, $end), 422, 'Çalışma günü seçilen haftanın içinde olmalıdır.');
            abort_if(! empty($assignment['work_shift_id']) && ! empty($assignment['status']), 422, 'Bir gün için mesai veya durum seçebilirsiniz.');

            if (! empty($assignment['work_shift_id'])) {
                $businessHour = $businessHours->get($date->isoWeekday());
                $workShift = $workShifts->get($assignment['work_shift_id']);
                abort_if(
                    ! $businessHour || $businessHour->is_closed
                    || substr($workShift->start_time, 0, 5) < substr($businessHour->opening_time, 0, 5)
                    || substr($workShift->end_time, 0, 5) > substr($businessHour->closing_time, 0, 5),
                    422,
                    'Seçilen mesai işletmenin çalışma saatleri dışında kalıyor.',
                );
            }
        }

        DB::transaction(function () use ($validated, $start, $end): void {
            EmployeeSchedule::query()->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])->delete();
            foreach ($validated['assignments'] as $assignment) {
                if (empty($assignment['work_shift_id']) && empty($assignment['status'])) {
                    continue;
                }
                EmployeeSchedule::create($assignment);
            }
        });

        return $this->index(new Request(['week_start' => $start->toDateString()]));
    }
}
