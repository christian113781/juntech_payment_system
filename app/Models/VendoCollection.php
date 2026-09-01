<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendoCollection extends Model
{
    use HasFactory;

    protected $table = 'vendo_collections';

    protected $fillable = [
        'partner_id',
        'collection_date',
        'total_amount',
        'share_amount',
        'owner_amount',
        'remarks',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'total_amount' => 'decimal:2',
        'share_amount' => 'decimal:2',
        'owner_amount' => 'decimal:2',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(VendoPartner::class, 'partner_id');
    }
}
