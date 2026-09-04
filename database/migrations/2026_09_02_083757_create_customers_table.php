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
        Schema::create('customers', function (Blueprint $table) {
           $table->id();

            $table->string('name');

            $table->foreignId('area_id')->constrained('areas')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->text('remarks')->nullable();

            // Fixed price assigned directly to this customer
            $table->decimal('monthly_price', 10, 2);
            
            // Latest day of the customer's billing cycle
            $table->date('latest_billing_date');

            // Length of each billing cycle (e.g. 32 days)
            $table->unsignedInteger('billing_cycle_days')->default(31);

            $table->enum('status', ['active', 'disconnected'])->default('active');

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
