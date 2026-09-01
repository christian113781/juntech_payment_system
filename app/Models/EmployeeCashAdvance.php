<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeCashAdvance extends Model
{
    protected $table = 'employee_cash_advances';

    protected $fillable = [
        'employee_id',
        'advance_date',
        'amount',
        'amount_paid',
        'balance',
    ];

    protected $casts = [
        'advance_date' => 'date',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(EmployeeCashAdvancePayment::class, 'cash_advance_id');
    }
}
