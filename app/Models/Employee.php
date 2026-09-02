<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'name',
        'position',
        'contact_number',
        'address',
        'daily_rate',
        'date_started',
        'status',
        'notes',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'date_started' => 'date',
    ];

    public function cashAdvances(): HasMany
    {
        return $this->hasMany(EmployeeCashAdvance::class, 'employee_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }
}
