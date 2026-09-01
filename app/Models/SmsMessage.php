<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    protected $fillable = [
        'recipient_name', 'destination', 'message', 'campaign_id', 'status',
        'provider_response', 'sent_by', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
            'sent_at' => 'datetime',
        ];
    }
}
