<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'daily_rate',
        'days_worked',
        'overtime_hours',
        'basic_salary',
        'overtime_amount',
        'other_earnings',
        'other_deductions',
        'cash_advance_deduction',
        'gross_salary',
        'net_salary',
        'remarks',
        'paid_at',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'days_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'other_earnings' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'cash_advance_deduction' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(EmployeeCashAdvancePayment::class, 'payroll_id');
    }
}
