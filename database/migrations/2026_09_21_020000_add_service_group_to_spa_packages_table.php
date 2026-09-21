<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('spa_packages', 'service_group_id')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE spa_packages ADD COLUMN service_group_id INTEGER NULL');

            return;
        }

        Schema::table('spa_packages', function (Blueprint $table) {
            $table->foreignId('service_group_id')
                ->nullable()
                ->after('id')
                ->constrained('service_groups')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('spa_packages', 'service_group_id')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE spa_packages DROP COLUMN service_group_id');

            return;
        }

        Schema::table('spa_packages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_group_id');
        });
    }
};
