<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $fillable = ['provider', 'username', 'password', 'source_addr', 'active'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
            'active' => 'boolean',
        ];
    }

    public function isConfigured(): bool
    {
        return filled($this->username) && filled($this->password);
    }
}
