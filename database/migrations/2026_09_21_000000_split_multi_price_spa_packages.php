<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $packages = DB::table('spa_packages')
                ->whereNotNull('alternative_price')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            foreach ($packages as $package) {
                $durations = $this->splitDurations((string) $package->duration_text);

                if (count($durations) !== 2) {
                    continue;
                }

                [$firstDuration, $secondDuration] = $durations;
                $baseName = (string) $package->name;
                $firstName = $baseName.' '.$firstDuration;
                $secondName = $baseName.' '.$secondDuration;
                $now = now();

                DB::table('spa_packages')->where('id', $package->id)->update([
                    'name' => $firstName,
                    'duration_text' => $firstDuration,
                    'alternative_price' => null,
                    'updated_at' => $now,
                ]);

                DB::table('spa_packages')->insert([
                    'name' => $secondName,
                    'duration_text' => $secondDuration,
                    'featured_contents' => $package->featured_contents,
                    'target_audience' => $package->target_audience,
                    'sort_order' => (int) $package->sort_order + 1,
                    'price' => $package->alternative_price,
                    'alternative_price' => null,
                    'created_at' => $package->created_at,
                    'updated_at' => $now,
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            $pairs = [
                ['Aromatherapy Massage', '60 Dakika', '90 Dakika'],
                ['Balinese Massage', '60 Dakika', '90 Dakika'],
                ['Reflexology Massage', '30 Dakika', '50 Dakika'],
                ['4-Hand Massage', '60 Dakika', '90 Dakika'],
                ['Ottoman Sultan Hammam', '60 Dakika', '75 Dakika'],
            ];

            foreach ($pairs as [$baseName, $firstDuration, $secondDuration]) {
                $first = DB::table('spa_packages')->where('name', $baseName.' '.$firstDuration)->first();
                $second = DB::table('spa_packages')->where('name', $baseName.' '.$secondDuration)->first();

                if (! $first || ! $second) {
                    continue;
                }

                DB::table('spa_packages')->where('id', $first->id)->update([
                    'name' => $baseName,
                    'duration_text' => str_replace(' Dakika', '', $firstDuration).' / '.$secondDuration,
                    'alternative_price' => $second->price,
                    'updated_at' => now(),
                ]);

                DB::table('spa_packages')->where('id', $second->id)->delete();
            }
        });
    }

    private function splitDurations(string $durationText): array
    {
        if (! str_contains($durationText, '/')) {
            return [];
        }

        $parts = array_map('trim', explode('/', str_ireplace('Dakika', '', $durationText)));

        if (count($parts) !== 2 || in_array('', $parts, true)) {
            return [];
        }

        return array_map(static fn (string $duration): string => $duration.' Dakika', $parts);
    }
};
