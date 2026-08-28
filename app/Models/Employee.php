<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'registry_no', 'personnel_no', 'hire_date',
        'termination_date', 'birth_date', 'blood_group', 'gender', 'phone', 'mobile_phone',
        'email', 'city', 'district', 'address', 'photo_url', 'status',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date:Y-m-d',
            'termination_date' => 'date:Y-m-d',
            'birth_date' => 'date:Y-m-d',
        ];
    }
}
