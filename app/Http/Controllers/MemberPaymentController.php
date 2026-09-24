<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Reservation;
use App\Services\TcmbExchangeRateService;
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

    public function store(Request $request, Member $member, TcmbExchangeRateService $exchangeRates): JsonResponse
    {
        $data = $request->validate([
            'reservation_id' => ['required', 'integer', 'exists:reservations,id'],
            'paid_at' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'currency' => ['nullable', Rule::in(['TRY', 'USD', 'EUR'])],
            'payment_type' => ['required', Rule::in(['cash', 'credit_card', 'transfer', 'room_charge', 'installment'])],
            'installment_count' => ['nullable', 'required_if:payment_type,installment', 'integer', 'min:1', 'max:3'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        $currency = $data['currency'] ?? 'EUR';
        $amount = round((float) $data['amount'], 2);
        $rates = null;
        try { $rates = $exchangeRates->ratesFor($data['paid_at']); } catch (\Throwable $error) {
            if ($currency !== 'EUR') throw ValidationException::withMessages(['currency' => $error->getMessage()]);
        }
        $rateToTry = $currency === 'TRY' ? 1.0 : ($rates[$currency] ?? null);
        $amountTry = $rateToTry === null ? null : round($amount * $rateToTry, 2);
        $amountEur = $currency === 'EUR' ? $amount : round($amountTry / $rates['EUR'], 2);
        $reservation = Reservation::query()->whereBelongsTo($member)->find($data['reservation_id']);
        if (!$reservation) {
            throw ValidationException::withMessages(['reservation_id' => 'Seçilen rezervasyon bu misafire ait değil.']);
        }
        $due = $this->reservationDue($reservation);
        $paid = (float) MemberPayment::where('reservation_id', $reservation->id)->sum(DB::raw('COALESCE(amount_eur, amount)'));
        $remaining = round(max(0, $due - $paid), 2);
        if ($amountEur > $remaining) {
            throw ValidationException::withMessages(['amount' => 'Tahsilat kalan borçtan fazla olamaz.']);
        }

        $payment = DB::transaction(function () use ($data, $member, $reservation, $currency, $amount, $rateToTry, $amountTry, $amountEur) {
            $cash = CashTransaction::create([
                'transaction_date' => $data['paid_at'],
                'description' => "Misafir tahsilatı: {$member->member_no} - {$member->full_name} - Rez #{$reservation->id}".($data['payment_type'] === 'installment' ? " - {$data['installment_count']} taksit" : ''),
                'type' => 'income',
                'amount' => $amountTry ?? $amount,
                'currency' => $amountTry === null ? $currency : 'TRY',
                'payment_type' => $data['payment_type'],
                'category_id' => null,
                'document_no' => 'REZ-'.$reservation->id,
            ]);

            return MemberPayment::create([
                'member_id' => $member->id,
                'reservation_id' => $reservation->id,
                'cash_transaction_id' => $cash->id,
                'paid_at' => $data['paid_at'],
                'amount' => $amount,
                'currency' => $currency,
                'exchange_rate_to_try' => $rateToTry,
                'amount_try' => $amountTry,
                'amount_eur' => $amountEur,
                'payment_type' => $data['payment_type'],
                'installment_count' => $data['payment_type'] === 'installment' ? $data['installment_count'] : null,
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
        try { $currentRates = app(TcmbExchangeRateService::class)->ratesFor(now('Europe/Istanbul')->toDateString(), false); }
        catch (\Throwable) { $currentRates = ['TRY' => 1.0]; }
        $reservations = Reservation::query()
            ->whereBelongsTo($member)
            ->with(['items' => fn ($query) => $query->where('currency', 'EUR')->orderBy('id')])
            ->orderByDesc('reservation_date')->orderByDesc('start_time')->get();
        $paidByReservation = MemberPayment::query()->where('member_id', $member->id)
            ->selectRaw('reservation_id, SUM(COALESCE(amount_eur, amount)) as total')
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
            'exchange_rates' => $currentRates,
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
            'exchange_rate_to_try' => $payment->exchange_rate_to_try,
            'amount_try' => $payment->amount_try,
            'amount_eur' => $payment->amount_eur,
            'payment_type' => $payment->payment_type,
            'installment_count' => $payment->installment_count,
            'note' => $payment->note,
        ];
    }
}
