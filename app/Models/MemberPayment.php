<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberPayment extends Model
{
    protected $fillable = [
        'member_id', 'reservation_id', 'cash_transaction_id', 'paid_at',
        'amount', 'currency', 'exchange_rate_to_try', 'amount_try', 'amount_eur',
        'payment_type', 'installment_count', 'note',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'date:Y-m-d',
            'amount' => 'decimal:2',
            'exchange_rate_to_try' => 'decimal:6',
            'amount_try' => 'decimal:2',
            'amount_eur' => 'decimal:2',
            'installment_count' => 'integer',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function cashTransaction(): BelongsTo
    {
        return $this->belongsTo(CashTransaction::class);
    }
}
