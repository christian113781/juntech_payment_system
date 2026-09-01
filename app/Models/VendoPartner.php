<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendoPartner extends Model
{
    use HasFactory;

    protected $table = 'vendo_partners';

    protected $fillable = [
        'area_id',
        'address',
        'name',
        'contact_number',
        'vendo_unit_id',
        'status',
        'share_rate',
        'last_collected_at',
        'collection_interval_days',
    ];

    protected $casts = [
        'share_rate' => 'decimal:2',
        'last_collected_at' => 'date',
        'collection_interval_days' => 'integer',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(VendoUnit::class, 'vendo_unit_id');
    }

    public function collections(): HasMany
    {
        return $this->hasMany(VendoCollection::class, 'partner_id');
    }
}
