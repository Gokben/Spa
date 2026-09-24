<?php

namespace App\Console\Commands;

use App\Services\TcmbExchangeRateService;
use Illuminate\Console\Command;

class RefreshExchangeRates extends Command
{
    protected $signature = 'exchange-rates:refresh';
    protected $description = 'TCMB günlük USD ve EUR kurlarını veritabanına kaydeder';

    public function handle(TcmbExchangeRateService $service): int
    {
        try {
            $result = $service->refresh();
            $this->info("TCMB kurları güncellendi: {$result['date']}");
            return self::SUCCESS;
        } catch (\Throwable $error) {
            $this->error($error->getMessage());
            return self::FAILURE;
        }
    }
}
