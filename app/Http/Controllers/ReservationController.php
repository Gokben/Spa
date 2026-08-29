<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Member;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $month = $request->validate(['month' => ['nullable', 'date_format:Y-m']])['month'] ?? now()->format('Y-m');
        $start = $month . '-01';
        $end = date('Y-m-d', strtotime($start . ' +1 month'));

        return response()->json(['data' => [
            'month' => $month,
            'reservations' => Reservation::query()->with(['member', 'employee'])->whereDate('reservation_date', '>=', $start)->whereDate('reservation_date', '<', $end)->orderBy('reservation_date')->orderBy('start_time')->get(),
            'members' => Member::query()->where('status', 'aktif')->orderBy('name')->get(['id', 'member_no', 'name', 'phone']),
            'employees' => Employee::query()->with('occupation')->where('status', 'aktif')->orderBy('first_name')->orderBy('last_name')->get(['id', 'first_name', 'last_name', 'occupation_id']),
        ]]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureAvailable($data);

        return response()->json(['data' => Reservation::create($data)->load(['member', 'employee'])], 201);
    }

    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureAvailable($data, $reservation);
        $reservation->update($data);

        return response()->json(['data' => $reservation->refresh()->load(['member', 'employee'])]);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'member_id' => ['nullable', 'integer', 'exists:members,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'guest_name' => ['required', 'string', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service_name' => ['required', 'string', 'max:190'],
            'reservation_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', Rule::in(['planned', 'confirmed', 'completed', 'cancelled', 'no_show'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        if (!empty($data['member_id'])) {
            $member = Member::find($data['member_id']);
            $data['guest_name'] = $member->name;
            $data['phone'] = $member->phone;
        }

        return $data;
    }

    private function ensureAvailable(array $data, ?Reservation $reservation = null): void
    {
        if (empty($data['employee_id']) || $data['status'] === 'cancelled') return;
        $conflict = Reservation::query()
            ->where('employee_id', $data['employee_id'])
            ->whereDate('reservation_date', $data['reservation_date'])
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->when($reservation, fn ($query) => $query->whereKeyNot($reservation->id))
            ->exists();
        if ($conflict) {
            throw ValidationException::withMessages(['start_time' => 'Seçilen personelin bu saat aralığında başka bir rezervasyonu var.']);
        }
    }
}
