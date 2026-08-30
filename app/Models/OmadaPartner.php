<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OmadaPartner extends Model
{
    use HasFactory;

    protected $table = 'omada_partners';

    protected $fillable = ['name', 'contact_number', 'area_id', 'address'];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function voucherBatches(): HasMany
    {
        return $this->hasMany(OmadaVoucherBatch::class, 'partner_id');
    }
}
