<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['name', 'company', 'phone', 'email', 'address', 'city', 'notes'];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
