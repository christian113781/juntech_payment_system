<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function omadaPartners(): HasMany
    {
        return $this->hasMany(OmadaPartner::class);
    }

    public function vendoPartners(): HasMany
    {
        return $this->hasMany(VendoPartner::class);
    }
}
