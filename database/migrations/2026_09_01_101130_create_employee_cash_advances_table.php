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
        Schema::create('employee_cash_advances', function (Blueprint $table) {
             $table->id();

            $table->foreignId('employee_id')
            ->constrained('employees')
            ->cascadeOnDelete();

            $table->date('advance_date');

            $table->decimal('amount', 10, 2);

            // Amount already deducted/repaid
            $table->decimal('amount_paid', 10, 2)
            ->default(0);

            // Remaining balance
            $table->decimal('balance', 10, 2)
            ->default(0);

            $table->timestamps();

            $table->index('employee_id');
            $table->index('advance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_cash_advances');
    }
};
