<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpaPackageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_spa_packages_can_be_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->getJson('/api/packages')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.name', 'Klasik Rahatlama Paketi');

        $packageId = $this->postJson('/api/packages', [
            'name' => 'Test Paketi',
            'duration_text' => '60 Dakika',
            'featured_contents' => 'Test içeriği',
            'target_audience' => 'Test misafirleri',
        ])->assertCreated()->assertJsonPath('data.name', 'Test Paketi')->json('data.id');

        $this->putJson("/api/packages/{$packageId}", [
            'name' => 'Güncel Test Paketi',
            'duration_text' => '75 Dakika',
            'featured_contents' => 'Güncel içerik',
            'target_audience' => 'Güncel misafirler',
        ])->assertOk()->assertJsonPath('data.duration_text', '75 Dakika');

        $this->deleteJson("/api/packages/{$packageId}")->assertNoContent();
        $this->assertDatabaseMissing('spa_packages', ['id' => $packageId]);
    }
}
