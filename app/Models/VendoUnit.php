<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class VendoUnit extends Model
{
    use HasFactory;

    protected $table = 'vendo_units';

    protected $fillable = [
        'name',
        'description',
        'key',
        'status',
        'condition_notes',
    ];

    public function partner(): HasOne
    {
        return $this->hasOne(VendoPartner::class, 'vendo_unit_id');
    }

    public function collections(): HasMany
    {
        return $this->hasMany(VendoCollection::class, 'vendo_id');
    }
}
