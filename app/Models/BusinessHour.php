<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $fillable = ['day_of_week', 'opening_time', 'closing_time', 'is_closed'];

    protected function casts(): array
    {
        return ['is_closed' => 'boolean'];
    }
}
