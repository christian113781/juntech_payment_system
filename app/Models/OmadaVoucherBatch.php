<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OmadaVoucherBatch extends Model
{
    use HasFactory;

    protected $table = 'omada_voucher_batches';

    protected $fillable = [
        'partner_id',
        'batch_code',
        'type',
        'requested_qty',
        'bonus_qty',
        'price_per_voucher',
        'generated_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'generated_date' => 'date',
        'price_per_voucher' => 'decimal:2',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(OmadaPartner::class, 'partner_id');
    }

    public function voucherCollections(): HasMany
    {
        return $this->hasMany(OmadaVoucherCollection::class, 'batch_id');
    }
}
