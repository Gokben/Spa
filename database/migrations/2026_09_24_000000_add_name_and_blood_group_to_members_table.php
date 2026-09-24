<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('first_name', 80)->nullable()->after('full_name');
            $table->string('last_name', 80)->nullable()->after('first_name');
            $table->string('blood_group', 3)->nullable()->after('birth_date');
        });

        DB::table('members')->select(['id', 'full_name'])->orderBy('id')->each(function (object $member): void {
            $parts = preg_split('/\s+/u', trim((string) $member->full_name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $lastName = count($parts) > 1 ? array_pop($parts) : null;
            DB::table('members')->where('id', $member->id)->update([
                'first_name' => implode(' ', $parts) ?: (string) $member->full_name,
                'last_name' => $lastName,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'blood_group']);
        });
    }
};
