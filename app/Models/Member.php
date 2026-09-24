<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_no', 'full_name', 'first_name', 'last_name', 'identity_number', 'occupation', 'birth_date', 'blood_group',
        'address', 'phone', 'email', 'emergency_contact_name', 'emergency_phone',
        'membership_type', 'duration_months', 'valid_from', 'valid_through',
        'payment_type', 'contract_amount', 'discount_type', 'discount_value', 'invoice_address', 'status',
    ];

    protected $hidden = ['identity_number'];

    protected function casts(): array
    {
        return [
            'identity_number' => 'encrypted',
            'birth_date' => 'date:Y-m-d',
            'valid_from' => 'date:Y-m-d',
            'valid_through' => 'date:Y-m-d',
            'contract_amount' => 'decimal:2',
            'discount_value' => 'decimal:2',
        ];
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(MemberMeasurement::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(MemberVisit::class);
    }
}
