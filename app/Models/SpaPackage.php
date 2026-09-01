<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpaPackage extends Model
{
    protected $fillable = [
        'name',
        'duration_text',
        'featured_contents',
        'target_audience',
        'sort_order',
    ];
}
