<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MemberPaymentController extends Controller
{
    public function index(Member $member): JsonResponse
    {
        return response()->json(['data' => $this->paymentData($member)]);
    }

    public function store(Request $request, Member $member): JsonResponse
    {
        $data = $request->validate([
            'reservation_id' => ['required', 'integer', 'exists:reservations,id'],
            'paid_at' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_type' => ['required', Rule::in(['cash', 'credit_card', 'transfer', 'room_charge'])],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        $reservation = Reservation::query()->whereBelongsTo($member)->find($data['reservation_id']);
        if (!$reservation) {
            throw ValidationException::withMessages(['reservation_id' => 'Seçilen rezervasyon bu misafire ait değil.']);
        }
        $due = $this->reservationDue($reservation);
        $paid = (float) MemberPayment::where('reservation_id', $reservation->id)->where('currency', 'EUR')->sum('amount');
        $remaining = round(max(0, $due - $paid), 2);
        if ((float) $data['amount'] > $remaining) {
            throw ValidationException::withMessages(['amount' => 'Tahsilat kalan borçtan fazla olamaz.']);
        }

        $payment = DB::transaction(function () use ($data, $member, $reservation) {
            $cash = CashTransaction::create([
                'transaction_date' => $data['paid_at'],
                'description' => "Misafir tahsilatı: {$member->member_no} - {$member->full_name} - Rez #{$reservation->id}",
                'type' => 'income',
                'amount' => $data['amount'],
                'currency' => 'EUR',
                'payment_type' => $data['payment_type'],
                'category_id' => null,
                'document_no' => 'REZ-'.$reservation->id,
            ]);

            return MemberPayment::create([
                'member_id' => $member->id,
                'reservation_id' => $reservation->id,
                'cash_transaction_id' => $cash->id,
                'paid_at' => $data['paid_at'],
                'amount' => $data['amount'],
                'currency' => 'EUR',
                'payment_type' => $data['payment_type'],
                'note' => $data['note'] ?? null,
            ]);
        });

        return response()->json(['data' => [
            'payment' => $this->paymentPayload($payment->load('reservation')),
            'account' => $this->paymentData($member),
        ]], 201);
    }

    public function destroy(Member $member, MemberPayment $payment): JsonResponse
    {
        abort_unless($payment->member_id === $member->id, 404);
        DB::transaction(function () use ($payment) {
            $cashId = $payment->cash_transaction_id;
            $payment->delete();
            CashTransaction::whereKey($cashId)->delete();
        });

        return response()->json([], 204);
    }

    private function paymentData(Member $member): array
    {
        $reservations = Reservation::query()
            ->whereBelongsTo($member)
            ->with(['items' => fn ($query) => $query->where('currency', 'EUR')->orderBy('id')])
            ->orderByDesc('reservation_date')->orderByDesc('start_time')->get();
        $paidByReservation = MemberPayment::query()->where('member_id', $member->id)
            ->where('currency', 'EUR')->selectRaw('reservation_id, SUM(amount) as total')
            ->groupBy('reservation_id')->pluck('total', 'reservation_id');
        $reservationRows = $reservations->map(function (Reservation $reservation) use ($paidByReservation) {
            $due = $this->reservationDue($reservation);
            $paid = (float) ($paidByReservation[$reservation->id] ?? 0);
            return [
                'id' => $reservation->id,
                'date' => $reservation->reservation_date?->format('Y-m-d'),
                'service_name' => $reservation->service_name,
                'total' => round($due, 2),
                'paid' => round($paid, 2),
                'remaining' => round(max(0, $due - $paid), 2),
                'currency' => 'EUR',
            ];
        })->values();
        $payments = MemberPayment::query()->where('member_id', $member->id)
            ->with('reservation:id,service_name')->orderByDesc('paid_at')->orderByDesc('id')->get()
            ->map(fn (MemberPayment $payment) => $this->paymentPayload($payment))->values();

        return [
            'member' => ['id' => $member->id, 'memberNo' => $member->member_no, 'name' => $member->full_name],
            'summary' => [
                'total' => round((float) $reservationRows->sum('total'), 2),
                'paid' => round((float) $reservationRows->sum('paid'), 2),
                'remaining' => round((float) $reservationRows->sum('remaining'), 2),
                'currency' => 'EUR',
            ],
            'reservations' => $reservationRows,
            'payments' => $payments,
        ];
    }

    private function reservationDue(Reservation $reservation): float
    {
        return round((float) $reservation->items->where('currency', 'EUR')->sum('unit_price'), 2);
    }

    private function paymentPayload(MemberPayment $payment): array
    {
        return [
            'id' => $payment->id,
            'reservation_id' => $payment->reservation_id,
            'service_name' => $payment->reservation?->service_name,
            'paid_at' => $payment->paid_at?->format('Y-m-d'),
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'payment_type' => $payment->payment_type,
            'note' => $payment->note,
        ];
    }
}
