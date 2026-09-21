<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        if (! DB::table('service_groups')->where('name', 'Üyelikler')->exists()) {
            DB::table('service_groups')->insert([
                'name' => 'Üyelikler',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $groups = DB::table('service_groups')->pluck('id', 'name');
        foreach ($this->packagesByGroup() as $group => $packages) {
            DB::table('spa_packages')
                ->whereIn('name', $packages)
                ->update(['service_group_id' => $groups[$group], 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        DB::table('spa_packages')
            ->whereIn('name', collect($this->packagesByGroup())->flatten()->all())
            ->update(['service_group_id' => null, 'updated_at' => now()]);
    }

    private function packagesByGroup(): array
    {
        return [
            'Masaj' => [
                'Swedish Massage',
                'Aromatherapy Massage 60 Dakika',
                'Aromatherapy Massage 90 Dakika',
                'Deep Tissue Massage',
                'Sport Massage',
                'Balinese Massage 60 Dakika',
                'Balinese Massage 90 Dakika',
                'Anti-Stress Massage',
                'Reflexology Massage 30 Dakika',
                'Reflexology Massage 50 Dakika',
                'Hot Stone Massage',
                'Hot Candle Massage',
                '4-Hand Massage 60 Dakika',
                '4-Hand Massage 90 Dakika',
                'Medical Massage',
                'Extra Time',
                'Head Massage',
                'Indian Head Massage',
                'Face Massage',
                'Foot Massage',
                'Back Massage',
            ],
            'Hamam' => [
                'Body Scrub & Bubble Wash Ritual',
                'Traditional Hammam Ritual',
                'Sofitel Sultan Dream',
                'Sofitel Exclusive Hammam',
                'Ottoman Sultan Hammam 60 Dakika',
                'Ottoman Sultan Hammam 75 Dakika',
                'Pure Escape Massage & Hammam',
            ],
            'Bakım' => [
                'Sofitel Express',
                'Jetlag Treatment',
                'Red Carpet Treatment',
                'Lux Harmony Massage VIP Room',
                'Express Facial Cleansing',
                'Classic Facial Treatments',
                'Relaxing Facial Ritual',
            ],
            'Üyelikler' => [
                'Daily Use',
                'Pool Access',
                '1 Month',
                '3 Month',
                '6 Month',
                '12 Month',
            ],
        ];
    }
};
