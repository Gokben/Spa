<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'rate_date', 'currency_code', 'currency_name', 'forex_buying', 'forex_selling',
        'banknote_buying', 'banknote_selling',
    ];

    protected function casts(): array
    {
        return [
            'rate_date' => 'date:Y-m-d',
            'forex_buying' => 'decimal:6',
            'forex_selling' => 'decimal:6',
            'banknote_buying' => 'decimal:6',
            'banknote_selling' => 'decimal:6',
        ];
    }
}
