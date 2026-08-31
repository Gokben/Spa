<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryDefinitionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_include_an_example_and_can_be_managed(): void
    {
        $this->actingAs(User::factory()->create());

        $exampleCategoryId = $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Masaj')
            ->json('data.0.id');

        $categoryId = $this->postJson('/api/categories', ['name' => 'Cilt Bakımı'])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Cilt Bakımı')
            ->json('data.id');

        $this->putJson("/api/categories/{$categoryId}", ['name' => 'Bakım'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Bakım');

        $this->postJson('/api/stock-items', [
            'code' => 'TEST-001',
            'name' => 'Test Ürünü',
            'category_ids' => [$exampleCategoryId, $categoryId],
            'unit' => 'Adet',
            'minimum_quantity' => 0,
            'purchase_price' => 0,
            'sale_price' => 0,
            'vat_rate' => 20,
            'status' => 'aktif',
        ])->assertCreated()
            ->assertJsonCount(2, 'data.categories');

        $this->getJson('/api/stock-items')
            ->assertOk()
            ->assertJsonCount(2, 'data.0.categories');

        $this->deleteJson("/api/categories/{$categoryId}")->assertNoContent();
        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);
    }
}
