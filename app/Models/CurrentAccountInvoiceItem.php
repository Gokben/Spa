<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrentAccountInvoiceItem extends Model
{
    protected $fillable = [
        'current_account_invoice_id',
        'stock_item_id',
        'description',
        'quantity',
        'discount_rate',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'discount_rate' => 'decimal:2',
            'unit_price' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(CurrentAccountInvoice::class, 'current_account_invoice_id');
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }
}
