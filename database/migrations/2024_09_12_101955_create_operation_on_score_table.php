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
        Schema::create('operation_on_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scorable_id');
            $table->string('scorable_type');
            $table->string('formula')->nullable();
            $table->json('conversion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_on_score');
    }
};
