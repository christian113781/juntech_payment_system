<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku', 'brand', 'unit',
        'product_image', 'reorder_level', 'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockBalance(): HasOne
    {
        return $this->hasOne(StockBalance::class);
    }

    // convenience accessor so you don't have to eager-load + null-check everywhere
    public function getQuantityOnHandAttribute(): int
    {
        return $this->stockBalance?->quantity_on_hand ?? 0;
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereHas('stockBalance', function ($q) {
            $q->whereColumn('quantity_on_hand', '<=', 'products.reorder_level');
        });
    }
}
