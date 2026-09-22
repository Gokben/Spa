<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberMeasurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberMeasurementController extends Controller
{
    public function index(Member $member): JsonResponse
    {
        return response()->json(['data' => [
            'member' => ['id' => $member->id, 'memberNo' => $member->member_no, 'name' => $member->full_name],
            'measurements' => $member->measurements()->latest('measured_at')->get(),
        ]]);
    }

    public function store(Request $request, Member $member): JsonResponse
    {
        $measurement = $member->measurements()->create($this->validated($request));

        return response()->json(['data' => $measurement], 201);
    }

    public function update(Request $request, Member $member, MemberMeasurement $measurement): JsonResponse
    {
        abort_unless($measurement->member_id === $member->id, 404);
        $measurement->update($this->validated($request));

        return response()->json(['data' => $measurement->refresh()]);
    }

    public function destroy(Member $member, MemberMeasurement $measurement): JsonResponse
    {
        abort_unless($measurement->member_id === $member->id, 404);
        $measurement->delete();

        return response()->json(['message' => 'Ölçüm kaydı silindi.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'measured_at' => ['required', 'date'], 'body_type' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['kadın', 'erkek', 'belirtilmedi'])], 'age' => ['nullable', 'integer', 'between:1,120'],
            'height_cm' => ['nullable', 'numeric', 'between:50,250'], 'weight_kg' => ['nullable', 'numeric', 'between:1,500'],
            'bmi' => ['nullable', 'numeric', 'between:1,100'], 'bmr_kj' => ['nullable', 'integer', 'between:1,50000'],
            'bmr_kcal' => ['nullable', 'integer', 'between:1,15000'], 'fat_percent' => ['nullable', 'numeric', 'between:0,100'],
            'fat_mass_kg' => ['nullable', 'numeric', 'between:0,500'], 'ffm_kg' => ['nullable', 'numeric', 'between:0,500'],
            'tbw_kg' => ['nullable', 'numeric', 'between:0,500'], 'impedance' => ['nullable', 'array'],
            'impedance.*' => ['nullable', 'numeric', 'between:0,5000'], 'segments' => ['nullable', 'array'],
            'segments.*.fat_percent' => ['nullable', 'numeric', 'between:0,100'],
            'segments.*.fat_mass_kg' => ['nullable', 'numeric', 'between:0,500'],
            'segments.*.ffm_kg' => ['nullable', 'numeric', 'between:0,500'],
            'segments.*.muscle_mass_kg' => ['nullable', 'numeric', 'between:0,500'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
