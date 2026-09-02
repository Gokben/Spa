<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurrentAccount extends Model
{
    protected $fillable = [
        'code',
        'title',
        'short_name',
        'tax_office',
        'tax_number',
        'type',
        'phone',
        'email',
        'authorized_person',
        'invoice_address',
        'company_detail',
        'has_internal_service',
        'has_external_service',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'has_internal_service' => 'boolean',
            'has_external_service' => 'boolean',
        ];
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(CurrentAccountInvoice::class);
    }
}
