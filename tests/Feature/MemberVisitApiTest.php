<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberVisitApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_membership_visit_can_be_saved_with_manual_entry_and_exit_times(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'member_no' => 'T-001',
            'full_name' => 'TEST MİSAFİR',
            'membership_type' => 'Aylık',
            'status' => 'aktif',
            'valid_from' => '2026-01-01',
            'valid_through' => '2026-12-31',
        ]);

        $this->actingAs($user)->postJson("/api/members/{$member->id}/visits", [
            'visit_type' => 'membership',
            'check_in_at' => '2026-09-24 09:15:00',
            'check_out_at' => '2026-09-24 11:45:00',
            'notes' => 'Elle girildi',
        ])->assertCreated()
            ->assertJsonPath('data.visit_type', 'membership')
            ->assertJsonPath('data.duration_minutes', 150);

        $this->assertDatabaseHas('member_visits', [
            'member_id' => $member->id,
            'check_in_at' => '2026-09-24 09:15:00',
            'check_out_at' => '2026-09-24 11:45:00',
            'notes' => 'Elle girildi',
        ]);
    }

    public function test_visit_times_can_be_corrected_manually(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'member_no' => 'T-002', 'full_name' => 'TEST ÜYE',
            'membership_type' => 'Aylık', 'status' => 'aktif',
            'valid_from' => '2026-01-01', 'valid_through' => '2026-12-31',
        ]);
        $visitId = $this->actingAs($user)->postJson("/api/members/{$member->id}/visits", [
            'visit_type' => 'membership', 'check_in_at' => '2026-09-24 10:00:00',
        ])->assertCreated()->json('data.id');

        $this->actingAs($user)->putJson("/api/members/{$member->id}/visits/{$visitId}", [
            'check_in_at' => '2026-09-24 10:10:00',
            'check_out_at' => '2026-09-24 12:20:00',
            'notes' => 'Saat düzeltildi',
        ])->assertOk()->assertJsonPath('data.duration_minutes', 130);
    }

    public function test_visit_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'member_no' => 'T-003', 'full_name' => 'SİLİNECEK KAYIT',
            'membership_type' => 'Aylık', 'status' => 'aktif',
            'valid_from' => '2026-01-01', 'valid_through' => '2026-12-31',
        ]);
        $visitId = $this->actingAs($user)->postJson("/api/members/{$member->id}/visits", [
            'visit_type' => 'membership', 'check_in_at' => '2026-09-24 13:00:00',
        ])->assertCreated()->json('data.id');

        $this->actingAs($user)
            ->deleteJson("/api/members/{$member->id}/visits/{$visitId}")
            ->assertOk();

        $this->assertDatabaseMissing('member_visits', ['id' => $visitId]);
    }
}
