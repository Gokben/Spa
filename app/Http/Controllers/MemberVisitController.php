<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberVisit;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MemberVisitController extends Controller
{
    public function index(Member $member): JsonResponse
    {
        $visits = MemberVisit::query()
            ->whereBelongsTo($member)
            ->with('employee:id,first_name,last_name')
            ->latest('check_in_at')
            ->limit(250)
            ->get()
            ->map(fn (MemberVisit $visit) => $this->payload($visit));

        return response()->json(['data' => [
            'visits' => $visits,
            'open_visit' => $visits->firstWhere('check_out_at', null),
        ]]);
    }

    public function store(Request $request, Member $member): JsonResponse
    {
        $data = $request->validate([
            'visit_type' => ['required', Rule::in(['membership', 'service', 'reservation'])],
            'reservation_id' => ['nullable', 'integer', 'exists:reservations,id'],
            'check_in_at' => ['nullable', 'date'],
            'check_out_at' => ['nullable', 'date', 'after_or_equal:check_in_at'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        if (empty($data['check_out_at']) && MemberVisit::query()->whereBelongsTo($member)->whereNull('check_out_at')->exists()) {
            throw ValidationException::withMessages(['visit' => 'Bu misafir için açık bir giriş zaten bulunuyor.']);
        }

        if ($data['visit_type'] === 'membership') {
            $today = today();
            if ($member->status !== 'aktif'
                || ($member->valid_from && $member->valid_from->isAfter($today))
                || ($member->valid_through && $member->valid_through->isBefore($today))) {
                throw ValidationException::withMessages(['membership' => 'Misafirin aktif ve geçerli bir üyeliği bulunmuyor.']);
            }
        }

        $reservation = null;
        if ($data['visit_type'] !== 'membership') {
            if (empty($data['reservation_id'])) {
                throw ValidationException::withMessages(['reservation_id' => 'Hizmet veya rezervasyon ziyareti için rezervasyon seçin.']);
            }
            $reservation = Reservation::query()->whereBelongsTo($member)->find($data['reservation_id']);
            if (! $reservation) {
                throw ValidationException::withMessages(['reservation_id' => 'Seçilen rezervasyon bu misafire ait değil.']);
            }
        }

        $visit = DB::transaction(fn () => MemberVisit::create([
            'member_id' => $member->id,
            'reservation_id' => $reservation?->id,
            'employee_id' => $reservation?->employee_id,
            'visit_type' => $data['visit_type'],
            'service_name' => $reservation?->service_name,
            'check_in_at' => $data['check_in_at'] ?? now(),
            'check_out_at' => $data['check_out_at'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]));

        return response()->json(['data' => $this->payload($visit->load('employee'))], 201);
    }

    public function update(Request $request, Member $member, MemberVisit $visit): JsonResponse
    {
        $this->ensureMember($member, $visit);
        $data = $request->validate([
            'check_in_at' => ['required', 'date'],
            'check_out_at' => ['nullable', 'date', 'after_or_equal:check_in_at'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);
        $visit->update($data);

        return response()->json(['data' => $this->payload($visit->refresh()->load('employee'))]);
    }

    public function checkout(Member $member, MemberVisit $visit): JsonResponse
    {
        $this->ensureMember($member, $visit);
        if ($visit->check_out_at) {
            throw ValidationException::withMessages(['visit' => 'Bu ziyaretin çıkışı daha önce yapılmış.']);
        }
        $visit->update(['check_out_at' => now()->isBefore($visit->check_in_at) ? $visit->check_in_at : now()]);

        return response()->json(['data' => $this->payload($visit->refresh()->load('employee'))]);
    }

    public function destroy(Member $member, MemberVisit $visit): JsonResponse
    {
        $this->ensureMember($member, $visit);
        $visit->delete();

        return response()->json(['message' => 'Giriş/çıkış kaydı silindi.']);
    }

    private function ensureMember(Member $member, MemberVisit $visit): void
    {
        abort_unless($visit->member_id === $member->id, 404);
    }

    private function payload(MemberVisit $visit): array
    {
        $checkIn = Carbon::parse($visit->check_in_at);
        $checkOut = $visit->check_out_at ? Carbon::parse($visit->check_out_at) : null;

        return [
            'id' => $visit->id,
            'visit_type' => $visit->visit_type,
            'reservation_id' => $visit->reservation_id,
            'service_name' => $visit->service_name,
            'employee_name' => $visit->employee
                ? trim($visit->employee->first_name.' '.$visit->employee->last_name)
                : null,
            'check_in_at' => $checkIn->toIso8601String(),
            'check_out_at' => $checkOut?->toIso8601String(),
            'duration_minutes' => $checkOut ? $checkIn->diffInMinutes($checkOut) : null,
            'notes' => $visit->notes,
        ];
    }
}
