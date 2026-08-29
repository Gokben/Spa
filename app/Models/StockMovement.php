<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = ['stock_item_id', 'type', 'quantity', 'movement_date', 'document_no', 'description'];

    protected function casts(): array
    {
        return ['quantity' => 'decimal:2', 'movement_date' => 'date:Y-m-d'];
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }
}
