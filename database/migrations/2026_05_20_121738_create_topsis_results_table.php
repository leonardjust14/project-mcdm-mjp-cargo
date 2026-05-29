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
        Schema::create('topsis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternative_id')->constrained('alternatives')->cascadeOnDelete();
            $table->decimal('d_positive', 15, 8)->nullable();
            $table->decimal('d_negative', 15, 8)->nullable();
            $table->decimal('preference_score', 15, 8)->nullable();
            $table->unsignedInteger('rank')->nullable();
            $table->timestamp('calculated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topsis_results');
    }
};
