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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnUpdate()->restrictOnDelete();

            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date');

            $table->enum('payment_method', ['cash', 'gcash'])->default('cash');

            $table->string('reference_number')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index('customer_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
