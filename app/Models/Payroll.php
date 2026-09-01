<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'pay_period_start',
        'pay_period_end',
        'gross_pay',
        'net_pay',
        'status',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'gross_pay' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(EmployeeCashAdvancePayment::class, 'payroll_id');
    }
}
