<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->date('rate_date');
            $table->string('currency_code', 3);
            $table->string('currency_name');
            $table->decimal('forex_buying', 18, 6)->nullable();
            $table->decimal('forex_selling', 18, 6)->nullable();
            $table->decimal('banknote_buying', 18, 6)->nullable();
            $table->decimal('banknote_selling', 18, 6)->nullable();
            $table->timestamps();
            $table->unique(['rate_date', 'currency_code']);
        });

        Schema::table('member_payments', function (Blueprint $table) {
            $table->decimal('exchange_rate_to_try', 18, 6)->nullable()->after('currency');
            $table->decimal('amount_try', 14, 2)->nullable()->after('exchange_rate_to_try');
            $table->decimal('amount_eur', 14, 2)->nullable()->after('amount_try');
        });

        DB::table('member_payments')->where('currency', 'EUR')->update([
            'amount_eur' => DB::raw('amount'),
        ]);
    }

    public function down(): void
    {
        Schema::table('member_payments', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate_to_try', 'amount_try', 'amount_eur']);
        });
        Schema::dropIfExists('exchange_rates');
    }
};
