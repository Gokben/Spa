<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member_payments', function (Blueprint $table) {
            $table->unsignedTinyInteger('installment_count')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('member_payments', function (Blueprint $table) {
            $table->dropColumn('installment_count');
        });
    }
};
