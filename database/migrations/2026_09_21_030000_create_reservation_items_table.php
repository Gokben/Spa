<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spa_package_id')->nullable()->constrained('spa_packages')->nullOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->string('type', 20);
            $table->string('name', 190);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->string('currency', 3);
            $table->timestamps();
            $table->index(['reservation_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
    }
};
