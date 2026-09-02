<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payroll_run_id')
            ->constrained('payroll_runs')
            ->cascadeOnDelete();

            $table->foreignId('employee_id')
            ->constrained('employees')
            ->cascadeOnDelete();

            // Employee rate during payroll generation
            $table->decimal('daily_rate', 10, 2);

            // Attendance
            $table->decimal('days_worked', 5, 2)
            ->default(0);

            $table->decimal('overtime_hours', 6, 2)
            ->default(0);

            // Salary
            $table->decimal('basic_salary', 10, 2)
                ->default(0);

            $table->decimal('overtime_amount', 10, 2)
            ->default(0);

            // Additional earnings and deductions
            $table->decimal('other_earnings', 10, 2)
            ->default(0);

            $table->decimal('other_deductions', 10, 2)
            ->default(0);

            // Cash advance deduction
            $table->decimal('cash_advance_deduction', 10, 2)
            ->default(0);

            $table->decimal('gross_salary', 10, 2)
            ->default(0);

            $table->decimal('net_salary', 10, 2)
            ->default(0);

            $table->text('remarks')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->unique([
            'payroll_run_id',
            'employee_id'
            ]);

            $table->index('employee_id');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
