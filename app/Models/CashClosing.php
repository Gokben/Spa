<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashClosing extends Model
{
    protected $fillable = ['closing_date', 'expected_balance', 'counted_balance', 'difference', 'note'];

    protected function casts(): array
    {
        return [
            'closing_date' => 'date:Y-m-d',
            'expected_balance' => 'decimal:2',
            'counted_balance' => 'decimal:2',
            'difference' => 'decimal:2',
        ];
    }
}
