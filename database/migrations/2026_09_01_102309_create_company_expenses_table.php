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
        Schema::create('company_expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expense_category_id')
            ->nullable()
            ->constrained('expense_categories')
            ->nullOnDelete();

            $table->date('expense_date');

            $table->string('description');

            $table->decimal('amount', 10, 2);

            $table->enum('payment_method', [
            'cash',
            'gcash',
            'bank'
            ])->default('cash');

            $table->string('reference_number')->nullable();

            // Uploaded receipt path
            $table->string('receipt_file')->nullable();

            $table->text('remarks')->nullable();

            // User who recorded the expense
            $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            $table->timestamps();

            $table->index('expense_category_id');
            $table->index('expense_date');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_expenses');
    }
};
