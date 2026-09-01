<?php

namespace Tests\Feature;

use App\Models\StockItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_entry_can_be_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $item = StockItem::create([
            'code' => 'TEST-001',
            'name' => 'Test Ürünü',
            'unit' => 'Adet',
            'minimum_quantity' => 0,
            'purchase_price' => 0,
            'sale_price' => 0,
            'vat_rate' => 20,
            'status' => 'aktif',
        ]);

        $movementId = $this->postJson('/api/stock-movements', [
            'stock_item_id' => $item->id,
            'type' => 'giris',
            'quantity' => 10,
            'movement_date' => '2026-09-01',
            'document_no' => 'G-001',
            'description' => 'İlk giriş',
        ])->assertCreated()->json('data.id');

        $this->putJson("/api/stock-movements/{$movementId}", [
            'stock_item_id' => $item->id,
            'type' => 'giris',
            'quantity' => 12,
            'movement_date' => '2026-09-02',
            'document_no' => 'G-002',
            'description' => 'Güncel giriş',
        ])->assertOk()->assertJsonPath('data.quantity', '12.00');

        $this->deleteJson("/api/stock-movements/{$movementId}")->assertNoContent();
        $this->assertDatabaseMissing('stock_movements', ['id' => $movementId]);
    }

    public function test_stock_entry_cannot_be_removed_when_it_would_make_balance_negative(): void
    {
        $this->actingAs(User::factory()->create());
        $item = StockItem::create([
            'code' => 'TEST-002',
            'name' => 'Bakiye Testi',
            'unit' => 'Adet',
            'minimum_quantity' => 0,
            'purchase_price' => 0,
            'sale_price' => 0,
            'vat_rate' => 20,
            'status' => 'aktif',
        ]);
        $entry = $item->movements()->create(['type' => 'giris', 'quantity' => 5, 'movement_date' => '2026-09-01']);
        $item->movements()->create(['type' => 'cikis', 'quantity' => 4, 'movement_date' => '2026-09-01']);

        $this->deleteJson("/api/stock-movements/{$entry->id}")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');
    }

    public function test_stock_exit_can_be_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $item = StockItem::create([
            'code' => 'TEST-003',
            'name' => 'Çıkış Testi',
            'unit' => 'Adet',
            'minimum_quantity' => 0,
            'purchase_price' => 0,
            'sale_price' => 0,
            'vat_rate' => 20,
            'status' => 'aktif',
        ]);
        $item->movements()->create(['type' => 'giris', 'quantity' => 10, 'movement_date' => '2026-09-01']);

        $movementId = $this->postJson('/api/stock-movements', [
            'stock_item_id' => $item->id,
            'type' => 'cikis',
            'quantity' => 4,
            'movement_date' => '2026-09-01',
            'document_no' => 'C-001',
            'description' => 'İlk çıkış',
        ])->assertCreated()->json('data.id');

        $this->putJson("/api/stock-movements/{$movementId}", [
            'stock_item_id' => $item->id,
            'type' => 'cikis',
            'quantity' => 6,
            'movement_date' => '2026-09-02',
            'document_no' => 'C-002',
            'description' => 'Güncel çıkış',
        ])->assertOk()->assertJsonPath('data.quantity', '6.00');

        $this->deleteJson("/api/stock-movements/{$movementId}")->assertNoContent();
        $this->assertDatabaseMissing('stock_movements', ['id' => $movementId]);
    }
}
