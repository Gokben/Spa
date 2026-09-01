<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spa_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190)->unique();
            $table->string('duration_text', 100);
            $table->text('featured_contents');
            $table->text('target_audience');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('spa_packages')->insert([
            ['name' => 'Klasik Rahatlama Paketi', 'duration_text' => '90 - 120 Dakika', 'featured_contents' => 'Islak alan + İsveç/Aroma masajı + İkramlar', 'target_audience' => 'İlk kez spa deneyimi yaşayacaklar', 'sort_order' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Geleneksel Hamam Paketi', 'duration_text' => '90 Dakika', 'featured_contents' => 'Kese + Köpük masajı + Nemlendirici yağ bakım', 'target_audience' => 'Derinlemesine vücut temizliği isteyenler', 'sort_order' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Çiftlere Özel Romantik Paket', 'duration_text' => '120 - 150 Dakika', 'featured_contents' => 'VIP çift odası + Jakuzi + Eş zamanlı masaj + Meyve tabağı', 'target_audience' => 'Yıldönümleri ve özel günler için çiftler', 'sort_order' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arındırıcı Güzellik Paketi', 'duration_text' => '150 - 180 Dakika', 'featured_contents' => 'Hamam + Bali masajı + Yüz/Cilt bakımı + Maske', 'target_audience' => 'Hem beden hem cilt yenilenmesi arayanlar', 'sort_order' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gelin / Bekarlığa Veda Paketi', 'duration_text' => '180+ Dakika', 'featured_contents' => 'Gelin hamamı ritüeli + Aromaterapi masajı + İkramlar', 'target_audience' => 'Düğün öncesi stres atmak isteyen gelinler', 'sort_order' => 50, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('spa_packages');
    }
};
