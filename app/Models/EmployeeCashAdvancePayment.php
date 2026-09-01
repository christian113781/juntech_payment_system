<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeCashAdvancePayment extends Model
{
    protected $table = 'employee_cash_advance_payments';

    protected $fillable = [
        'cash_advance_id',
        'payroll_id',
        'amount',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function cashAdvance(): BelongsTo
    {
        return $this->belongsTo(EmployeeCashAdvance::class, 'cash_advance_id');
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }
}
