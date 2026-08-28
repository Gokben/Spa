<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Employee::query()->orderBy('first_name')->orderBy('last_name')->get()]);
    }

    public function show(Employee $employee): JsonResponse
    {
        return response()->json(['data' => $employee]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['data' => Employee::create($this->validated($request))], 201);
    }

    public function update(Request $request, Employee $employee): JsonResponse
    {
        $employee->update($this->validated($request, $employee));

        return response()->json(['data' => $employee->refresh()]);
    }

    public function uploadPhoto(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate(['photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);

        if ($employee->photo_url && str_starts_with($employee->photo_url, '/storage/employees/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $employee->photo_url));
        }

        $path = $validated['photo']->store('employees', 'public');
        $employee->update(['photo_url' => Storage::url($path)]);

        return response()->json(['data' => $employee->refresh()]);
    }

    private function validated(Request $request, ?Employee $employee = null): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'registry_no' => ['nullable', 'string', 'max:50', Rule::unique('employees')->ignore($employee?->id)],
            'personnel_no' => ['nullable', 'string', 'max:50', Rule::unique('employees')->ignore($employee?->id)],
            'hire_date' => ['nullable', 'date'],
            'termination_date' => ['nullable', 'date', 'after_or_equal:hire_date'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'blood_group' => ['nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', '0+', '0-'])],
            'gender' => ['nullable', Rule::in(['Kadın', 'Erkek', 'Belirtmek İstemiyor'])],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile_phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:2000'],
            'photo_url' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['aktif', 'ayrıldı', 'yasaklı'])],
        ]);
    }
}
