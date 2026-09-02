<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurrentAccountInvoice extends Model
{
    protected $fillable = [
        'current_account_id',
        'movement_date',
        'movement_type',
        'invoice_no',
        'quantity',
        'total_amount',
        'total_with_tax',
        'undiscounted_amount',
        'discount_amount',
        'discount_rate',
        'payment_type',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'movement_date' => 'date:Y-m-d',
            'quantity' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'total_with_tax' => 'decimal:2',
            'undiscounted_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'discount_rate' => 'decimal:2',
        ];
    }

    public function currentAccount(): BelongsTo
    {
        return $this->belongsTo(CurrentAccount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CurrentAccountInvoiceItem::class);
    }
}
