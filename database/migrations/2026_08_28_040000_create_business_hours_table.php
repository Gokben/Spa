<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day_of_week')->unique();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        $now = now();
        DB::table('business_hours')->insert(array_map(
            fn (int $day) => [
                'day_of_week' => $day,
                'opening_time' => '09:00',
                'closing_time' => '22:00',
                'is_closed' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            range(1, 7),
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
