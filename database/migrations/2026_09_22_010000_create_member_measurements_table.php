<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->dateTime('measured_at');
            $table->string('body_type', 30)->nullable();
            $table->string('gender', 20)->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->decimal('height_cm', 6, 2)->nullable();
            $table->decimal('weight_kg', 6, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->unsignedInteger('bmr_kj')->nullable();
            $table->unsignedInteger('bmr_kcal')->nullable();
            $table->decimal('fat_percent', 5, 2)->nullable();
            $table->decimal('fat_mass_kg', 6, 2)->nullable();
            $table->decimal('ffm_kg', 6, 2)->nullable();
            $table->decimal('tbw_kg', 6, 2)->nullable();
            $table->json('impedance')->nullable();
            $table->json('segments')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->index(['member_id', 'measured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_measurements');
    }
};
