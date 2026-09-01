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
        Schema::create('vendo_partners', function (Blueprint $table) {
            $table->id();

            $table->foreignId('area_id')
                  ->constrained('areas')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('address')->nullable();
            $table->string('name');
            $table->string('contact_number')->nullable();

            $table->foreignId('vendo_unit_id')
                  ->nullable()
                  ->unique()
                  ->constrained('vendo_units')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->enum('status', ['unassigned', 'active', 'inactive'])
                  ->default('unassigned');

            $table->decimal('share_rate', 5, 2)->unsigned()->default(30.00);

            $table->date('last_collected_at')->nullable();
            $table->integer('collection_interval_days')->unsigned()->default(32);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendo_partners');
    }
};
