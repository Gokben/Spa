<?php

namespace Tests\Feature;

use App\Models\CurrentAccount;
use App\Models\CurrentAccountInvoice;
use App\Models\CurrentAccountInvoiceItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrentAccountApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_accounts_can_be_created_updated_listed_and_deleted(): void
    {
        $payload = [
            'code' => 'CR-01',
            'title' => 'Akmaz Sağlık Ürünleri Ltd. Şti.',
            'short_name' => 'Akmaz Sağlık',
            'tax_office' => 'Atışalanı',
            'tax_number' => '0320546817',
            'type' => 'tedarikci',
            'phone' => '0532 656 95 58',
            'email' => 'satis@example.com',
            'authorized_person' => 'Sami Aktaş',
            'invoice_address' => 'İstanbul',
            'company_detail' => 'Tedarikçi firma',
            'has_internal_service' => true,
            'has_external_service' => false,
            'status' => 'aktif',
        ];

        $id = $this->postJson('/api/current-accounts', $payload)
            ->assertCreated()
            ->assertJsonPath('data.code', 'CR-01')
            ->json('data.id');

        $this->getJson('/api/current-accounts')
            ->assertOk()
            ->assertJsonPath('data.0.invoices_count', 0);

        $this->putJson("/api/current-accounts/{$id}", [...$payload, 'short_name' => 'Akmaz'])
            ->assertOk()
            ->assertJsonPath('data.short_name', 'Akmaz');

        $this->deleteJson("/api/current-accounts/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('current_accounts', ['id' => $id]);
    }

    public function test_account_detail_returns_invoice_based_movements_with_items(): void
    {
        $account = CurrentAccount::create([
            'code' => 'CR-02', 'title' => 'Tedarikçi', 'short_name' => 'Tedarikçi',
            'type' => 'tedarikci', 'status' => 'aktif',
        ]);
        $invoice = CurrentAccountInvoice::create([
            'current_account_id' => $account->id,
            'movement_date' => '2026-09-02',
            'movement_type' => 'giris',
            'invoice_no' => 'FTR-001',
            'quantity' => 2,
            'total_amount' => 1000,
            'total_with_tax' => 1200,
            'undiscounted_amount' => 1250,
            'discount_amount' => 50,
            'discount_rate' => 4,
        ]);
        CurrentAccountInvoiceItem::create([
            'current_account_invoice_id' => $invoice->id,
            'description' => 'Masaj yağı',
            'quantity' => 2,
            'discount_rate' => 4,
            'unit_price' => 625,
        ]);

        $this->getJson("/api/current-accounts/{$account->id}")
            ->assertOk()
            ->assertJsonPath('data.invoices.0.invoice_no', 'FTR-001')
            ->assertJsonPath('data.invoices.0.items.0.description', 'Masaj yağı');
    }
}
