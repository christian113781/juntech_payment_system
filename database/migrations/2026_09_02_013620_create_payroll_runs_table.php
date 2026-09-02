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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();

            $table->date('period_start');
            $table->date('period_end');

            $table->string('attendance_file')->nullable();

            $table->string('attendance_file_original_name')
            ->nullable();

            $table->unsignedBigInteger('attendance_file_size')
            ->nullable();

            $table->timestamp('generated_at');

            $table->timestamps();

            $table->index([
            'period_start',
            'period_end'
    ]);

    $table->index('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
