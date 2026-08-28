<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    protected $fillable = ['employee_id', 'work_date', 'work_shift_id', 'status'];
}
