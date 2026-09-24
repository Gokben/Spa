<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Services\TcmbExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        return response()->json(['data' => $this->latestRates($validated['date'] ?? null)]);
    }

    public function refresh(TcmbExchangeRateService $exchangeRates): JsonResponse
    {
        $exchangeRates->refresh();

        return response()->json(['data' => $this->latestRates()]);
    }

    private function latestRates(?string $date = null): array
    {
        return collect(['USD', 'EUR'])
            ->map(function (string $code) use ($date) {
                $query = ExchangeRate::query()->where('currency_code', $code);

                if ($date) {
                    $query->whereDate('rate_date', '<=', $date);
                }

                return $query->latest('rate_date')->first();
            })
            ->filter()
            ->values()
            ->all();
    }
}
