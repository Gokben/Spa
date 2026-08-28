<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'registry_no', 'personnel_no', 'occupation_id', 'work_group_id', 'hire_date',
        'termination_date', 'birth_date', 'blood_group', 'gender', 'phone', 'mobile_phone',
        'email', 'city', 'district', 'address', 'photo_url', 'status',
    ];

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }

    public function workGroup(): BelongsTo
    {
        return $this->belongsTo(WorkGroup::class);
    }

    protected function casts(): array
    {
        return [
            'hire_date' => 'date:Y-m-d',
            'termination_date' => 'date:Y-m-d',
            'birth_date' => 'date:Y-m-d',
        ];
    }
}
