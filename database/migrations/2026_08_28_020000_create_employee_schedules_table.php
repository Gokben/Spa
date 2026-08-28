<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->unsignedInteger('work_shift_id')->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'work_date']);
            $table->foreign('work_shift_id')->references('id')->on('work_shifts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
