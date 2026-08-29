<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransaction extends Model
{
    protected $fillable = ['transaction_date', 'description', 'type', 'amount', 'payment_type', 'category_id', 'document_no'];

    protected function casts(): array
    {
        return ['transaction_date' => 'date:Y-m-d', 'amount' => 'decimal:2'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CashCategory::class);
    }
}
