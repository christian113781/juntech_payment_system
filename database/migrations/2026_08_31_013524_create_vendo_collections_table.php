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
        Schema::create('vendo_collections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('partner_id')
                  ->constrained('vendo_partners')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->date('collection_date');
            $table->decimal('total_amount', 10, 2)->unsigned()->default(0.00);
            $table->decimal('share_amount', 10, 2)->unsigned()->default(0.00);
            $table->decimal('owner_amount', 10, 2)->unsigned()->default(0.00);
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendo_collections');
    }
};
