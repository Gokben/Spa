<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class ServiceGroup extends Model
{
    protected $fillable = ['name'];

    public function packages(): HasMany
    {
        return $this->hasMany(SpaPackage::class);
    }
}
