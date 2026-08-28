<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('registry_no', 50)->nullable()->unique();
            $table->string('personnel_no', 50)->nullable()->unique();
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('blood_group', 3)->nullable();
            $table->string('gender', 24)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->string('status', 20)->default('aktif')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
