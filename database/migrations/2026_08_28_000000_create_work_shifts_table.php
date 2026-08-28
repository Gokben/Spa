<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('sort_order');
            $table->timestamps();
        });

        $now = now();
        $shifts = [
                [24, '06:30', '15:00'], [4, '07:00', '15:00'], [5, '07:30', '16:00'],
                [25, '08:00', '16:00'], [22, '08:30', '16:30'], [35, '09:00', '13:00'],
                [2, '09:00', '13:00'], [6, '09:00', '17:00'], [1, '09:00', '18:00'],
                [7, '10:00', '18:00'], [16, '11:00', '19:30'], [17, '12:00', '20:00'],
                [26, '12:30', '21:00'], [18, '13:00', '21:00'], [27, '13:30', '22:00'],
                [19, '14:00', '22:00'], [40, '14:30', '22:30'], [10, '14:30', '23:00'],
        ];

        DB::table('work_shifts')->insert(array_map(
            fn (int $index, array $shift) => ['id' => $shift[0], 'start_time' => $shift[1], 'end_time' => $shift[2], 'sort_order' => $index + 1, 'created_at' => $now, 'updated_at' => $now],
            array_keys($shifts),
            $shifts,
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('work_shifts');
    }
};
