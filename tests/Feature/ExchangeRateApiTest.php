<?php

namespace Tests\Feature;

use App\Models\ExchangeRate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExchangeRateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_exchange_rates_can_be_listed_and_refreshed_from_tcmb(): void
    {
        $user = User::factory()->create();
        ExchangeRate::create([
            'rate_date' => '2026-09-23',
            'currency_code' => 'USD',
            'currency_name' => 'ABD DOLARI',
            'forex_buying' => 40.10,
            'forex_selling' => 40.20,
        ]);

        $this->actingAs($user)->getJson('/api/exchange-rates')
            ->assertOk()
            ->assertJsonPath('data.0.currency_code', 'USD');

        Http::fake(['www.tcmb.gov.tr/*' => Http::response(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Tarih_Date Tarih="24.09.2026">
  <Currency CurrencyCode="USD"><Isim>ABD DOLARI</Isim><ForexBuying>40.3000</ForexBuying><ForexSelling>40.4000</ForexSelling><BanknoteBuying>40.2000</BanknoteBuying><BanknoteSelling>40.5000</BanknoteSelling></Currency>
  <Currency CurrencyCode="EUR"><Isim>EURO</Isim><ForexBuying>47.7000</ForexBuying><ForexSelling>47.8000</ForexSelling><BanknoteBuying>47.6000</BanknoteBuying><BanknoteSelling>47.9000</BanknoteSelling></Currency>
</Tarih_Date>
XML, 200)]);

        $this->actingAs($user)->postJson('/api/exchange-rates/refresh')
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $this->assertDatabaseHas('exchange_rates', [
            'rate_date' => '2026-09-24',
            'currency_code' => 'EUR',
        ]);
    }

    public function test_exchange_rates_can_be_listed_for_a_payment_date(): void
    {
        $user = User::factory()->create();

        foreach (['2026-09-22' => 48.10, '2026-09-24' => 48.70] as $date => $rate) {
            ExchangeRate::create([
                'rate_date' => $date,
                'currency_code' => 'USD',
                'currency_name' => 'ABD DOLARI',
                'forex_buying' => $rate,
                'forex_selling' => $rate + 0.10,
            ]);
        }

        $this->actingAs($user)->getJson('/api/exchange-rates?date=2026-09-23')
            ->assertOk()
            ->assertJsonPath('data.0.rate_date', '2026-09-22')
            ->assertJsonPath('data.0.forex_buying', '48.100000');
    }
}
