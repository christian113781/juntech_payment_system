<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area_id',
        'contact_number',
        'address',
        'remarks',
        'monthly_price',
        'latest_billing_date',
        'billing_cycle_days',
        'status',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'latest_billing_date' => 'date',
        'billing_cycle_days' => 'integer',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
