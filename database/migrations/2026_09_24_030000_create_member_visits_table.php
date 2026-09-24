<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visit_type', 20)->default('membership');
            $table->string('service_name', 190)->nullable();
            $table->dateTime('check_in_at');
            $table->dateTime('check_out_at')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->index(['member_id', 'check_in_at']);
            $table->index(['member_id', 'check_out_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_visits');
    }
};
