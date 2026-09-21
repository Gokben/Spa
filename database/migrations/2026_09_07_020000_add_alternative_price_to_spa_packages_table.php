<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spa_packages', function (Blueprint $table) {
            $table->decimal('alternative_price', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('spa_packages', function (Blueprint $table) {
            $table->dropColumn('alternative_price');
        });
    }
};
