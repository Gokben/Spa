<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OccupationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Occupation::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['data' => Occupation::create($this->validated($request))], 201);
    }

    public function update(Request $request, Occupation $occupation): JsonResponse
    {
        $occupation->update($this->validated($request, $occupation));

        return response()->json(['data' => $occupation->refresh()]);
    }

    public function destroy(Occupation $occupation): JsonResponse
    {
        $occupation->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?Occupation $occupation = null): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('occupations')->ignore($occupation?->id)]]);
    }
}
