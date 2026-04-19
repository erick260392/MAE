<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $fillable = ['customer_id', 'folio', 'status', 'total', 'notes', 'delivery_time', 'conditions', 'seen_at'];

    protected $casts = ['seen_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (Quote $quote) {
            $last = static::max('id') ?? 0;
            $quote->folio = 'MAE-'.str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}
