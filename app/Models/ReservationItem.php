<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationItem extends Model
{
    protected $fillable = [
        'spa_package_id', 'stock_item_id', 'type', 'name', 'unit_price', 'currency',
    ];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2'];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function spaPackage(): BelongsTo
    {
        return $this->belongsTo(SpaPackage::class);
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }
}
