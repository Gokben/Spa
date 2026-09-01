<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 30)->default('verimor');
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->string('source_addr', 20)->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_name')->nullable();
            $table->string('destination', 20);
            $table->text('message');
            $table->string('campaign_id')->nullable()->index();
            $table->string('status', 30)->default('pending')->index();
            $table->json('provider_response')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
        Schema::dropIfExists('sms_settings');
    }
};
