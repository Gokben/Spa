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
            'memberNo' => 'T-001', 'name' => 'TEST ÜYE', 'identity' => '00000000000',
            'occupation' => 'TEST', 'birthDate' => '1990-01-01', 'address' => 'Test adresi',
            'phone' => '0500 000 00 00', 'email' => 'member@example.test',
            'emergencyName' => 'TEST YAKINI', 'emergencyPhone' => '0500 000 00 01',
            'membershipType' => 'Yıllık', 'durationMonths' => 12,
            'validFrom' => '2026-01-01', 'validThrough' => '2027-01-01',
            'paymentType' => 'Kredi Kartı', 'contractAmount' => 1000,
            'invoiceAddress' => 'Test adresi', 'status' => 'aktif',
        ];

        $this->actingAs($user)->putJson("/api/members/{$member->id}", $payload)
            ->assertOk()->assertJsonPath('data.name', 'TEST ÜYE');

        $this->assertNotSame('00000000000', DB::table('members')->where('id', $member->id)->value('identity_number'));
    }

    private function member(): Member
    {
        return Member::create([
            'member_no' => 'T-001', 'full_name' => 'ÖRNEK ÜYE',
            'membership_type' => 'Aylık', 'status' => 'aktif',
        ]);
    }
}
