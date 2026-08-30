<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OmadaVoucherCollection extends Model
{
    use HasFactory;
    protected $table = 'omada_voucher_collections';

    protected $fillable = ['batch_id', 'collection_date', 'total_amount', 'remarks'];

    protected $casts = [
        'collection_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function voucherBatch(): BelongsTo
    {
        return $this->belongsTo(OmadaVoucherBatch::class, 'batch_id');
    }
}
