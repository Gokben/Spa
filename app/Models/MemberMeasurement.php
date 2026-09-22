<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberMeasurement extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'measured_at' => 'datetime:Y-m-d H:i',
            'height_cm' => 'decimal:2', 'weight_kg' => 'decimal:2', 'bmi' => 'decimal:2',
            'fat_percent' => 'decimal:2', 'fat_mass_kg' => 'decimal:2',
            'ffm_kg' => 'decimal:2', 'tbw_kg' => 'decimal:2',
            'impedance' => 'array', 'segments' => 'array',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
