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
        Schema::create('employee_cash_advance_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cash_advance_id')
            ->constrained('employee_cash_advances')
            ->cascadeOnDelete();

            // Payroll table is not created yet; keep as a plain nullable ID until it exists.
            $table->unsignedBigInteger('payroll_id')->nullable();

            $table->decimal('amount', 10, 2);

            $table->date('payment_date');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index('cash_advance_id');
            $table->index('payroll_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_cash_advance_payments');
    }
};
