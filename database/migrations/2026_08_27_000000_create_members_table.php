<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id(); $table->string('member_no', 30)->unique(); $table->string('full_name', 150)->index();
            $table->text('identity_number')->nullable(); $table->string('occupation', 100)->nullable();
            $table->date('birth_date')->nullable(); $table->text('address')->nullable();
            $table->string('phone', 30)->nullable()->index(); $table->string('email', 190)->nullable();
            $table->string('emergency_contact_name', 150)->nullable(); $table->string('emergency_phone', 30)->nullable();
            $table->string('membership_type', 80); $table->unsignedSmallInteger('duration_months')->nullable();
            $table->date('valid_from')->nullable(); $table->date('valid_through')->nullable()->index();
            $table->string('payment_type', 30)->nullable(); $table->decimal('contract_amount', 12, 2)->nullable();
            $table->text('invoice_address')->nullable(); $table->string('status', 20)->default('aktif')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
