<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($password = env('SEED_ADMIN_PASSWORD')) {
            User::query()->updateOrCreate(['email' => env('SEED_ADMIN_EMAIL', 'admin@spa.test')], [
                'name' => 'SPA Yöneticisi', 'password' => Hash::make($password),
            ]);
        }

        Member::query()->updateOrCreate(['member_no' => '035'], [
            'full_name' => 'DENİZ YILMAZ', 'identity_number' => null, 'occupation' => 'ÖRNEK MESLEK',
            'birth_date' => '1990-01-01', 'address' => 'Örnek Mah. No:1 İstanbul', 'phone' => '0500 000 00 01',
            'email' => 'deniz.yilmaz@example.test', 'emergency_contact_name' => 'ÖRNEK KİŞİ (Yakını)',
            'emergency_phone' => '0500 000 00 02', 'membership_type' => 'Süresiz', 'duration_months' => 1,
            'valid_from' => '2026-07-18', 'valid_through' => '2026-08-18', 'payment_type' => 'Kredi Kartı',
            'contract_amount' => 7500, 'invoice_address' => 'Örnek Mah. No:1 İstanbul', 'status' => 'aktif',
        ]);
    }
}
