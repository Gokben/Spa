<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        $now = now();
        DB::table('service_groups')->insert([
            ['name' => 'Masaj', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hamam', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bakım', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_groups');
    }
};
