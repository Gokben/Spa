<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'member_id', 'employee_id', 'guest_name', 'phone', 'service_name',
        'reservation_date', 'start_time', 'end_time', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['reservation_date' => 'date:Y-m-d'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
