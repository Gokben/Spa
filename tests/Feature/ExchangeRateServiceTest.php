<?php

namespace Tests\Feature;

use App\Models\ExchangeRate;
use App\Services\TcmbExchangeRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExchangeRateServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tcmb_rates_are_saved_and_can_be_read_for_conversion(): void
    {
        Http::fake(['www.tcmb.gov.tr/*' => Http::response(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Tarih_Date Tarih="24.09.2026">
  <Currency CurrencyCode="USD"><Isim>ABD DOLARI</Isim><ForexBuying>40.1000</ForexBuying><ForexSelling>40.2000</ForexSelling><BanknoteBuying>40.0000</BanknoteBuying><BanknoteSelling>40.3000</BanknoteSelling></Currency>
  <Currency CurrencyCode="EUR"><Isim>EURO</Isim><ForexBuying>47.5000</ForexBuying><ForexSelling>47.6000</ForexSelling><BanknoteBuying>47.4000</BanknoteBuying><BanknoteSelling>47.7000</BanknoteSelling></Currency>
</Tarih_Date>
XML, 200)]);

        $service = app(TcmbExchangeRateService::class);
        $service->refresh();

        $this->assertDatabaseHas('exchange_rates', ['rate_date' => '2026-09-24', 'currency_code' => 'USD']);
        $this->assertSame(40.1, $service->ratesFor('2026-09-24', false)['USD']);
        $this->assertSame(47.5, $service->ratesFor('2026-09-24', false)['EUR']);
        $this->assertCount(2, ExchangeRate::all());
    }
}
