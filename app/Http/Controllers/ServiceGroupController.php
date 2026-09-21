<?php

namespace App\Http\Controllers;

use App\Models\ServiceGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServiceGroupController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => ServiceGroup::query()->orderBy('id')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['data' => ServiceGroup::create($this->validated($request))], 201);
    }

    public function update(Request $request, ServiceGroup $serviceGroup): JsonResponse
    {
        $serviceGroup->update($this->validated($request, $serviceGroup));

        return response()->json(['data' => $serviceGroup->refresh()]);
    }

    public function destroy(ServiceGroup $serviceGroup): JsonResponse
    {
        DB::transaction(function () use ($serviceGroup): void {
            $serviceGroup->packages()->update(['service_group_id' => null]);
            $serviceGroup->delete();
        });

        return response()->json([], 204);
    }

    private function validated(Request $request, ?ServiceGroup $serviceGroup = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('service_groups')->ignore($serviceGroup?->id)],
        ]);
    }
}
