<?php

namespace App\Http\Controllers;

use App\Models\WorkShift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkShiftController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => WorkShift::query()->orderBy('sort_order')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $shift = WorkShift::create([
            ...$this->validated($request),
            'sort_order' => (WorkShift::max('sort_order') ?? 0) + 1,
        ]);

        return response()->json(['data' => $shift], 201);
    }

    public function update(Request $request, WorkShift $workShift): JsonResponse
    {
        $workShift->update($this->validated($request, $workShift));

        return response()->json(['data' => $workShift->refresh()]);
    }

    public function destroy(WorkShift $workShift): JsonResponse
    {
        $workShift->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?WorkShift $workShift = null): array
    {
        return $request->validate([
            'id' => ['required', 'integer', 'min:1', Rule::unique('work_shifts', 'id')->ignore($workShift?->id)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);
    }
}
