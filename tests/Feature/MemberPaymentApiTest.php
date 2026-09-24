<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\ExchangeRate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MemberPaymentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_is_linked_to_member_reservation_and_cash(): void
    {
        $user = User::factory()->create();
        [$member, $reservationId] = $this->memberWithReservation();

        $response = $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId,
            'paid_at' => '2026-09-22',
            'amount' => 100,
            'payment_type' => 'credit_card',
            'note' => 'İlk tahsilat',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.payment.reservation_id', $reservationId)
            ->assertJsonPath('data.account.summary.total', 170)
            ->assertJsonPath('data.account.summary.paid', 100)
            ->assertJsonPath('data.account.summary.remaining', 70);
        $this->assertDatabaseHas('member_payments', ['member_id' => $member->id, 'reservation_id' => $reservationId, 'amount' => 100, 'currency' => 'EUR']);
        $this->assertDatabaseHas('cash_transactions', ['type' => 'income', 'amount' => 100, 'currency' => 'EUR', 'document_no' => 'REZ-'.$reservationId]);
    }

    public function test_payment_cannot_exceed_remaining_debt_or_use_another_members_reservation(): void
    {
        $user = User::factory()->create();
        [$member, $reservationId] = $this->memberWithReservation();
        [$otherMember, $otherReservationId] = $this->memberWithReservation('T-002');

        $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId, 'paid_at' => '2026-09-22',
            'amount' => 171, 'payment_type' => 'cash',
        ])->assertUnprocessable()->assertJsonValidationErrors('amount');

        $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $otherReservationId, 'paid_at' => '2026-09-22',
            'amount' => 10, 'payment_type' => 'cash',
        ])->assertUnprocessable()->assertJsonValidationErrors('reservation_id');
    }

    public function test_deleting_payment_also_removes_linked_cash_transaction(): void
    {
        $user = User::factory()->create();
        [$member, $reservationId] = $this->memberWithReservation();
        $paymentId = $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId, 'paid_at' => '2026-09-22',
            'amount' => 50, 'payment_type' => 'transfer',
        ])->json('data.payment.id');
        $cashId = DB::table('member_payments')->where('id', $paymentId)->value('cash_transaction_id');

        $this->actingAs($user)->deleteJson("/api/members/{$member->id}/payments/{$paymentId}")->assertNoContent();

        $this->assertDatabaseMissing('member_payments', ['id' => $paymentId]);
        $this->assertDatabaseMissing('cash_transactions', ['id' => $cashId]);
    }

    public function test_installment_payment_is_limited_to_three_installments(): void
    {
        $user = User::factory()->create();
        [$member, $reservationId] = $this->memberWithReservation();

        $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId, 'paid_at' => '2026-09-22',
            'amount' => 50, 'payment_type' => 'installment', 'installment_count' => 4,
        ])->assertUnprocessable()->assertJsonValidationErrors('installment_count');

        $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId, 'paid_at' => '2026-09-22',
            'amount' => 50, 'payment_type' => 'installment', 'installment_count' => 3,
        ])->assertCreated()
            ->assertJsonPath('data.payment.payment_type', 'installment')
            ->assertJsonPath('data.payment.installment_count', 3);

        $this->assertDatabaseHas('member_payments', [
            'member_id' => $member->id, 'payment_type' => 'installment', 'installment_count' => 3,
        ]);
    }

    public function test_foreign_currency_payment_keeps_original_amount_and_turkish_lira_value(): void
    {
        $user = User::factory()->create();
        [$member, $reservationId] = $this->memberWithReservation();
        foreach ([['USD', 40], ['EUR', 50]] as [$code, $rate]) {
            ExchangeRate::create([
                'rate_date' => '2026-09-22', 'currency_code' => $code, 'currency_name' => $code,
                'forex_buying' => $rate, 'forex_selling' => $rate,
            ]);
        }

        $response = $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId, 'paid_at' => '2026-09-22',
            'amount' => 100, 'currency' => 'USD', 'payment_type' => 'cash',
        ])->assertCreated();

        $response->assertJsonPath('data.payment.currency', 'USD')
            ->assertJsonPath('data.payment.amount_try', '4000.00')
            ->assertJsonPath('data.payment.amount_eur', '80.00');
        $this->assertDatabaseHas('cash_transactions', ['amount' => 4000, 'currency' => 'TRY']);
    }

    public function test_member_and_reservation_with_payment_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        [$member, $reservationId] = $this->memberWithReservation();

        $this->actingAs($user)->postJson("/api/members/{$member->id}/payments", [
            'reservation_id' => $reservationId, 'paid_at' => '2026-09-22',
            'amount' => 50, 'payment_type' => 'cash',
        ])->assertCreated();

        $this->actingAs($user)->deleteJson("/api/reservations/{$reservationId}")
            ->assertUnprocessable();
        $this->actingAs($user)->deleteJson("/api/members/{$member->id}")
            ->assertUnprocessable();

        $this->assertDatabaseHas('members', ['id' => $member->id]);
        $this->assertDatabaseHas('reservations', ['id' => $reservationId]);
    }

    private function memberWithReservation(string $memberNo = 'T-001'): array
    {
        $member = Member::create(['member_no' => $memberNo, 'full_name' => 'TEST MİSAFİR', 'membership_type' => 'Aylık', 'status' => 'aktif']);
        $reservationId = DB::table('reservations')->insertGetId([
            'member_id' => $member->id, 'guest_name' => $member->full_name,
            'service_name' => 'Masaj ve Hamam', 'reservation_date' => '2026-09-22',
            'start_time' => '10:00', 'end_time' => '12:00', 'status' => 'completed',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('reservation_items')->insert([
            ['reservation_id' => $reservationId, 'type' => 'package', 'name' => 'Masaj', 'unit_price' => 95, 'currency' => 'EUR', 'created_at' => now(), 'updated_at' => now()],
            ['reservation_id' => $reservationId, 'type' => 'package', 'name' => 'Hamam', 'unit_price' => 75, 'currency' => 'EUR', 'created_at' => now(), 'updated_at' => now()],
        ]);

        return [$member, $reservationId];
    }
}
