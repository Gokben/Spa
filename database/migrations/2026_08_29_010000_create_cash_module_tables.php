<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('opening_balance', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('cash_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('type', 10);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unique(['name', 'type']);
        });

        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('description', 255);
            $table->string('type', 10);
            $table->decimal('amount', 14, 2);
            $table->string('payment_type', 30);
            $table->foreignId('category_id')->nullable()->constrained('cash_categories')->restrictOnDelete();
            $table->string('document_no', 100)->nullable();
            $table->timestamps();
            $table->index(['transaction_date', 'type']);
        });

        Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->date('closing_date')->unique();
            $table->decimal('expected_balance', 14, 2);
            $table->decimal('counted_balance', 14, 2);
            $table->decimal('difference', 14, 2);
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });

        DB::table('cash_settings')->insert(['id' => 1, 'opening_balance' => 0, 'created_at' => now(), 'updated_at' => now()]);
        foreach ([['Satış', 'income'], ['Üyelik', 'income'], ['Spa Hizmeti', 'income'], ['Ürün Satışı', 'income'], ['Maaş', 'expense'], ['Fatura', 'expense'], ['Kira', 'expense'], ['Sarf Malzeme', 'expense']] as [$name, $type]) {
            DB::table('cash_categories')->insert(['name' => $name, 'type' => $type, 'active' => true, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_closings');
        Schema::dropIfExists('cash_transactions');
        Schema::dropIfExists('cash_categories');
        Schema::dropIfExists('cash_settings');
    }
};
