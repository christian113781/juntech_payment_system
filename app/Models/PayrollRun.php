<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends Model
{
    protected $table = 'payroll_runs';

    protected $fillable = [
        'period_start',
        'period_end',
        'attendance_file',
        'attendance_file_original_name',
        'attendance_file_size',
        'generated_at',
        'attendance_data',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
        'attendance_file_size' => 'integer',
        'attendance_data' => 'array',
    ];

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'payroll_run_id');
    }
}
