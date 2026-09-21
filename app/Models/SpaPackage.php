<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class SpaPackage extends Model
{
    protected $fillable = [
        'name',
        'duration_text',
        'featured_contents',
        'target_audience',
        'sort_order',
        'price',
        'service_group_id',
    ];

    public function serviceGroup(): BelongsTo
    {
        return $this->belongsTo(ServiceGroup::class);
    }

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }
}
