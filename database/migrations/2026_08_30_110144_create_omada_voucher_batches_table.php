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
        Schema::create('omada_voucher_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('omada_partners')->onDelete('cascade');
            $table->string('batch_code')->unique();
            $table->enum('type', ['FREE', 'SALE', 'SALE + FREE'])->default('FREE');
            $table->unsignedInteger('requested_qty');
            $table->unsignedInteger('bonus_qty')->default(5);
            $table->decimal('price_per_voucher', 10, 2)->default(10.00);
            $table->date('generated_date');
            $table->enum('status', ['pending', 'delivered', 'paid', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omada_voucher_batches');
    }
};
