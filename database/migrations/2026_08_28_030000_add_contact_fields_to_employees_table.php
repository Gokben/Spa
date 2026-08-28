<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('gender');
            $table->string('mobile_phone', 30)->nullable()->after('phone');
            $table->string('email')->nullable()->after('mobile_phone');
            $table->string('city', 100)->nullable()->after('email');
            $table->string('district', 100)->nullable()->after('city');
            $table->text('address')->nullable()->after('district');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['phone', 'mobile_phone', 'email', 'city', 'district', 'address']);
        });
    }
};
