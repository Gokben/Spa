<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockItem extends Model
{
    protected $fillable = [
        'code', 'name', 'category', 'brand', 'unit', 'minimum_quantity',
        'purchase_price', 'sale_price', 'vat_rate', 'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'minimum_quantity' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'vat_rate' => 'decimal:2',
        ];
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
