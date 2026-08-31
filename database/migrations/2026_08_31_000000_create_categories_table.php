<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        Schema::create('category_stock_item', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'stock_item_id']);
        });

        DB::table('categories')->insert([
            'name' => 'Masaj',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('stock_items')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->orderBy('id')
            ->get(['id', 'category'])
            ->each(function (object $item): void {
                DB::table('categories')->updateOrInsert(
                    ['name' => $item->category],
                    ['updated_at' => now(), 'created_at' => now()]
                );

                $categoryId = DB::table('categories')->where('name', $item->category)->value('id');
                DB::table('category_stock_item')->insertOrIgnore([
                    'category_id' => $categoryId,
                    'stock_item_id' => $item->id,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_stock_item');
        Schema::dropIfExists('categories');
    }
};
