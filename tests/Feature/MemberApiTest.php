<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MemberApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_members(): void
    {
        $this->getJson('/api/members')->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_member_summaries(): void
    {
        $user = User::factory()->create();
        $member = $this->member();

        $this->actingAs($user)->getJson('/api/members')
            ->assertOk()
            ->assertJsonPath('data.0.id', $member->id)
            ->assertJsonMissingPath('data.0.identity')
            ->assertJsonMissingPath('data.0.address');
    }

    public function test_authenticated_user_can_view_and_update_member_card(): void
    {
        $user = User::factory()->create();
        $member = $this->member();

        $this->actingAs($user)->getJson("/api/members/{$member->id}")
            ->assertOk()->assertJsonPath('data.memberNo', 'T-001');

        $payload = [
            'memberNo' => 'T-001', 'firstName' => 'TEST', 'lastName' => 'ÜYE', 'identity' => '00000000000',
            'occupation' => 'TEST', 'birthDate' => '1990-01-01', 'bloodGroup' => 'A+', 'address' => 'Test adresi',
            'phone' => '0500 000 00 00', 'email' => 'member@example.test',
            'emergencyName' => 'TEST YAKINI', 'emergencyPhone' => '0500 000 00 01',
            'membershipType' => 'Yıllık', 'durationMonths' => 12,
            'validFrom' => '2026-01-01', 'validThrough' => '2027-01-01',
            'paymentType' => 'Kredi Kartı', 'contractAmount' => 1000,
            'invoiceAddress' => 'Test adresi', 'status' => 'aktif',
        ];

        $this->actingAs($user)->putJson("/api/members/{$member->id}", $payload)
            ->assertOk()
            ->assertJsonPath('data.name', 'TEST ÜYE')
            ->assertJsonPath('data.firstName', 'TEST')
            ->assertJsonPath('data.lastName', 'ÜYE')
            ->assertJsonPath('data.bloodGroup', 'A+');

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'first_name' => 'TEST',
            'last_name' => 'ÜYE',
            'full_name' => 'TEST ÜYE',
            'blood_group' => 'A+',
        ]);

        $this->assertNotSame('00000000000', DB::table('members')->where('id', $member->id)->value('identity_number'));
    }

    public function test_authenticated_user_can_delete_member(): void
    {
        $user = User::factory()->create();
        $member = $this->member();

        $this->actingAs($user)->deleteJson("/api/members/{$member->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Misafir kaydı silindi.');

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }

    public function test_identity_number_must_contain_exactly_eleven_digits(): void
    {
        $user = User::factory()->create();
        $member = $this->member();
        $payload = [
            'memberNo' => 'T-001', 'firstName' => 'TEST', 'lastName' => 'ÜYE',
            'identity' => '1234567890', 'membershipType' => 'Aylık', 'status' => 'aktif',
        ];

        $this->actingAs($user)->putJson("/api/members/{$member->id}", $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['identity']);

        $payload['identity'] = '1234567890A';
        $this->actingAs($user)->putJson("/api/members/{$member->id}", $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['identity']);
    }

    public function test_authenticated_user_can_list_each_reserved_service_for_member(): void
    {
        $user = User::factory()->create();
        $member = $this->member();
        $reservationId = DB::table('reservations')->insertGetId([
            'member_id' => $member->id,
            'guest_name' => $member->full_name,
            'service_name' => 'Klasik Rahatlama Paketi',
            'reservation_date' => '2026-09-22',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('reservation_items')->insert([
            ['reservation_id' => $reservationId, 'spa_package_id' => 1, 'stock_item_id' => null, 'type' => 'package', 'name' => 'İsveç Masajı', 'unit_price' => 95, 'currency' => 'EUR', 'created_at' => now(), 'updated_at' => now()],
            ['reservation_id' => $reservationId, 'spa_package_id' => 2, 'stock_item_id' => null, 'type' => 'package', 'name' => 'Hamam Ritüeli', 'unit_price' => 75, 'currency' => 'EUR', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->actingAs($user)->getJson("/api/members/{$member->id}/services")
            ->assertOk()
            ->assertJsonPath('data.member.memberNo', 'T-001')
            ->assertJsonCount(2, 'data.services')
            ->assertJsonPath('data.services.0.service_name', 'İsveç Masajı')
            ->assertJsonPath('data.services.1.service_name', 'Hamam Ritüeli')
            ->assertJsonPath('data.services.0.reservation_id', $reservationId)
            ->assertJsonPath('data.services.0.status', 'completed');
    }

    private function member(): Member
    {
        return Member::create([
            'member_no' => 'T-001', 'full_name' => 'ÖRNEK ÜYE', 'first_name' => 'ÖRNEK', 'last_name' => 'ÜYE',
            'membership_type' => 'Aylık', 'status' => 'aktif',
        ]);
    }
}
