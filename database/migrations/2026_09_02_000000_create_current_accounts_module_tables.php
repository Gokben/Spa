<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('current_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('title', 190);
            $table->string('short_name', 120);
            $table->string('tax_office', 120)->nullable();
            $table->string('tax_number', 20)->nullable();
            $table->string('type', 20)->default('tedarikci');
            $table->string('phone', 30)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('authorized_person', 150)->nullable();
            $table->text('invoice_address')->nullable();
            $table->text('company_detail')->nullable();
            $table->boolean('has_internal_service')->default(false);
            $table->boolean('has_external_service')->default(false);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('current_account_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('current_account_id')->constrained()->cascadeOnDelete();
            $table->date('movement_date');
            $table->string('movement_type', 20);
            $table->string('invoice_no', 100)->nullable();
            $table->decimal('quantity', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('total_with_tax', 14, 2)->default(0);
            $table->decimal('undiscounted_amount', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('discount_rate', 6, 2)->default(0);
            $table->string('payment_type', 80)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['current_account_id', 'movement_date']);
        });

        Schema::create('current_account_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('current_account_invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description', 255);
            $table->decimal('quantity', 14, 2)->default(0);
            $table->decimal('discount_rate', 6, 2)->default(0);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('current_account_invoice_items');
        Schema::dropIfExists('current_account_invoices');
        Schema::dropIfExists('current_accounts');
    }
};
