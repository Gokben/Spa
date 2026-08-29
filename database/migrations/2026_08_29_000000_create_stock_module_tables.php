<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('code', 80)->unique();
            $table->string('name', 190);
            $table->string('category', 120)->nullable();
            $table->string('brand', 120)->nullable();
            $table->string('unit', 30)->default('Adet');
            $table->decimal('minimum_quantity', 12, 2)->default(0);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(20);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained()->cascadeOnDelete();
            $table->string('type', 10);
            $table->decimal('quantity', 12, 2);
            $table->date('movement_date');
            $table->string('document_no', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['stock_item_id', 'movement_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_items');
    }
};
