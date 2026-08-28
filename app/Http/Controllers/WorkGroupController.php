<?php

namespace App\Http\Controllers;

use App\Models\WorkGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkGroupController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => WorkGroup::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['data' => WorkGroup::create($this->validated($request))], 201);
    }

    public function update(Request $request, WorkGroup $workGroup): JsonResponse
    {
        $workGroup->update($this->validated($request, $workGroup));

        return response()->json(['data' => $workGroup->refresh()]);
    }

    public function destroy(WorkGroup $workGroup): JsonResponse
    {
        $workGroup->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?WorkGroup $workGroup = null): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('work_groups')->ignore($workGroup?->id)]]);
    }
}
