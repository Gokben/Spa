<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TcmbExchangeRateService
{
    public function refresh(): array
    {
        $response = Http::timeout(12)->retry(2, 300)->get('https://www.tcmb.gov.tr/kurlar/today.xml');
        if (!$response->successful()) throw new RuntimeException('TCMB kurları alınamadı.');

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response->body());
        if ($xml === false) throw new RuntimeException('TCMB XML verisi okunamadı.');

        $rateDate = CarbonImmutable::createFromFormat('d.m.Y', trim((string) $xml['Tarih']), 'Europe/Istanbul')->toDateString();
        $saved = [];
        foreach ($xml->Currency as $currency) {
            $code = (string) $currency['CurrencyCode'];
            if (!in_array($code, ['USD', 'EUR'], true)) continue;
            $saved[$code] = ExchangeRate::updateOrCreate(
                ['rate_date' => $rateDate, 'currency_code' => $code],
                [
                    'currency_name' => trim((string) $currency->Isim),
                    'forex_buying' => $this->decimal($currency->ForexBuying),
                    'forex_selling' => $this->decimal($currency->ForexSelling),
                    'banknote_buying' => $this->decimal($currency->BanknoteBuying),
                    'banknote_selling' => $this->decimal($currency->BanknoteSelling),
                ],
            );
        }
        if (count($saved) !== 2) throw new RuntimeException('TCMB yanıtında USD veya EUR kuru eksik.');

        return ['date' => $rateDate, 'rates' => $saved];
    }

    public function ratesFor(string $date, bool $refreshToday = true): array
    {
        $date = CarbonImmutable::parse($date, 'Europe/Istanbul')->toDateString();
        $rates = $this->latestRates($date);
        $ratesAreFromRequestedDate = count($rates) === 2
            && collect($rates)->every(fn (ExchangeRate $rate) => $rate->rate_date?->format('Y-m-d') === $date);
        if ($refreshToday && $date === now('Europe/Istanbul')->toDateString() && !$ratesAreFromRequestedDate) {
            try { $this->refresh(); } catch (\Throwable) { /* Son kayıtlı kurlar kullanılacak. */ }
            $rates = $this->latestRates($date);
        }
        if (count($rates) < 2) throw new RuntimeException('USD ve EUR için kayıtlı TCMB kuru bulunamadı. Kur güncellemesini çalıştırın.');

        return ['TRY' => 1.0, 'USD' => (float) $rates['USD']->forex_buying, 'EUR' => (float) $rates['EUR']->forex_buying];
    }

    private function latestRates(string $date): array
    {
        $result = [];
        foreach (['USD', 'EUR'] as $code) {
            $rate = ExchangeRate::query()->where('currency_code', $code)->whereDate('rate_date', '<=', $date)->latest('rate_date')->first();
            if ($rate) $result[$code] = $rate;
        }
        return $result;
    }

    private function decimal(mixed $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : str_replace(',', '.', $value);
    }
}
