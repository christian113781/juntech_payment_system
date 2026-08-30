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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('sku')->nullable();
        $table->string('brand')->nullable();
        $table->string('unit')->nullable(); // e.g. "Piece", "Box", "Kilogram"
        $table->string('product_image')->nullable();
        $table->unsignedInteger('reorder_level')->default(1);
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
