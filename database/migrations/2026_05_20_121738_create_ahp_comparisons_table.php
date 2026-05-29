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
        Schema::create('ahp_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_i_id')->constrained('criteria')->cascadeOnDelete();
            $table->foreignId('criteria_j_id')->constrained('criteria')->cascadeOnDelete();
            $table->decimal('value', 10, 4);
            $table->timestamps();

            $table->unique(['criteria_i_id', 'criteria_j_id'], 'unique_comparison');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_comparisons');
    }
};
