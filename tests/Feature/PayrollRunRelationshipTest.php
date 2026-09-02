<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollRun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayrollRunRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_payroll_can_belong_to_a_payroll_run(): void
    {
        $run = PayrollRun::create([
            'period_start' => '2026-09-01',
            'period_end' => '2026-09-30',
            'generated_at' => now(),
        ]);

        $employee = Employee::create([
            'name' => 'Juan Dela Cruz',
            'position' => 'Technician',
            'contact_number' => '09171234567',
            'address' => 'Test Street',
            'daily_rate' => 750.00,
            'date_started' => '2026-01-15',
            'status' => 'active',
        ]);

        $payroll = Payroll::create([
            'payroll_run_id' => $run->id,
            'employee_id' => $employee->id,
            'daily_rate' => 750.00,
            'days_worked' => 20.00,
            'overtime_hours' => 5.00,
            'basic_salary' => 15000.00,
            'overtime_amount' => 2500.00,
            'other_earnings' => 0.00,
            'other_deductions' => 0.00,
            'cash_advance_deduction' => 0.00,
            'gross_salary' => 17500.00,
            'net_salary' => 17500.00,
        ]);

        $this->assertEquals($run->id, $payroll->payrollRun->id);
        $this->assertCount(1, $run->fresh()->payrolls);
    }
}
