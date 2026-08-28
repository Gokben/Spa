<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    protected $fillable = ['id', 'start_time', 'end_time', 'sort_order'];

    public $incrementing = false;
}
