<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberMeasurementApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_measurement_can_be_created_listed_updated_and_deleted(): void
    {
        $user = User::factory()->create();
        $member = Member::create(['member_no' => 'M-001', 'full_name' => 'ÖLÇÜM TEST', 'membership_type' => 'Aylık', 'status' => 'aktif']);
        $payload = [
            'measured_at' => '2026-09-22 15:04', 'body_type' => 'standard', 'gender' => 'erkek',
            'age' => 22, 'height_cm' => 188, 'weight_kg' => 126, 'bmi' => 35.6,
            'bmr_kj' => 11238, 'bmr_kcal' => 2686, 'fat_percent' => 31.5,
            'fat_mass_kg' => 39.7, 'ffm_kg' => 86.3, 'tbw_kg' => 63.2,
            'impedance' => ['whole_body' => 519, 'right_leg' => 218],
            'segments' => ['right_leg' => ['fat_percent' => 28.3, 'muscle_mass_kg' => 14.4]],
            'comment' => 'Takip ölçümü',
        ];

        $id = $this->actingAs($user)->postJson("/api/members/{$member->id}/measurements", $payload)
            ->assertCreated()->assertJsonPath('data.fat_percent', '31.50')->json('data.id');

        $this->actingAs($user)->getJson("/api/members/{$member->id}/measurements")
            ->assertOk()->assertJsonCount(1, 'data.measurements')->assertJsonPath('data.measurements.0.impedance.whole_body', 519);

        $payload['comment'] = 'Güncellendi';
        $this->actingAs($user)->putJson("/api/members/{$member->id}/measurements/{$id}", $payload)
            ->assertOk()->assertJsonPath('data.comment', 'Güncellendi');

        $this->actingAs($user)->deleteJson("/api/members/{$member->id}/measurements/{$id}")->assertOk();
        $this->assertDatabaseMissing('member_measurements', ['id' => $id]);
    }
}
