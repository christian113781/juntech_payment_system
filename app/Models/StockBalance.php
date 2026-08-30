<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBalance extends Model
{
    protected $fillable = ['product_id', 'quantity_on_hand'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
