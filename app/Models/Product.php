<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['category_id', 'sku', 'name', 'slug', 'description', 'application', 'price', 'stock', 'unit', 'image', 'active'];

    protected $casts = ['active' => 'boolean', 'price' => 'decimal:2'];

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if ($product->slug === null || $product->slug === '' || $product->isDirty('name')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->getKey());
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug !== '' ? $baseSlug : 'producto';
        $suffix = 2;

        while (static::query()
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug !== '' ? "{$baseSlug}-{$suffix}" : "producto-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
