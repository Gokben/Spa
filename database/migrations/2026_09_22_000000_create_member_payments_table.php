<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->string('currency', 3)->default('TRY')->after('amount');
        });

        Schema::create('member_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained()->restrictOnDelete();
            $table->foreignId('cash_transaction_id')->unique()->constrained()->restrictOnDelete();
            $table->date('paid_at');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_type', 30);
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->index(['member_id', 'paid_at']);
            $table->index(['reservation_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_payments');
        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
