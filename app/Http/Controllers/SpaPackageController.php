<?php

namespace App\Http\Controllers;

use App\Models\SpaPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpaPackageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => SpaPackage::query()->orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['sort_order'] = (int) SpaPackage::query()->max('sort_order') + 10;

        return response()->json(['data' => SpaPackage::create($data)], 201);
    }

    public function update(Request $request, SpaPackage $package): JsonResponse
    {
        $package->update($this->validated($request, $package));

        return response()->json(['data' => $package->refresh()]);
    }

    public function destroy(SpaPackage $package): JsonResponse
    {
        $package->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?SpaPackage $package = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:190', Rule::unique('spa_packages')->ignore($package?->id)],
            'duration_text' => ['required', 'string', 'max:100'],
            'featured_contents' => ['required', 'string', 'max:1000'],
            'target_audience' => ['required', 'string', 'max:1000'],
        ]);
    }
}
