<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BusinessHourController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => BusinessHour::query()->orderBy('day_of_week')->get()]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hours' => ['required', 'array', 'size:7'],
            'hours.*.day_of_week' => ['required', 'integer', 'between:1,7', 'distinct'],
            'hours.*.opening_time' => ['nullable', 'date_format:H:i'],
            'hours.*.closing_time' => ['nullable', 'date_format:H:i'],
            'hours.*.is_closed' => ['required', 'boolean'],
        ]);

        if (collect($validated['hours'])->pluck('day_of_week')->sort()->values()->all() !== range(1, 7)) {
            throw ValidationException::withMessages(['hours' => 'Haftanın yedi günü eksiksiz gönderilmelidir.']);
        }

        foreach ($validated['hours'] as $index => $hour) {
            if (! $hour['is_closed'] && (! $hour['opening_time'] || ! $hour['closing_time'])) {
                throw ValidationException::withMessages(["hours.$index.opening_time" => 'Açık günlerde açılış ve kapanış saati zorunludur.']);
            }

            if (! $hour['is_closed'] && $hour['closing_time'] <= $hour['opening_time']) {
                throw ValidationException::withMessages(["hours.$index.closing_time" => 'Kapanış saati açılış saatinden sonra olmalıdır.']);
            }
        }

        DB::transaction(function () use ($validated): void {
            foreach ($validated['hours'] as $hour) {
                BusinessHour::query()->updateOrCreate(
                    ['day_of_week' => $hour['day_of_week']],
                    [
                        'opening_time' => $hour['is_closed'] ? null : $hour['opening_time'],
                        'closing_time' => $hour['is_closed'] ? null : $hour['closing_time'],
                        'is_closed' => $hour['is_closed'],
                    ],
                );
            }
        });

        return $this->index();
    }
}
