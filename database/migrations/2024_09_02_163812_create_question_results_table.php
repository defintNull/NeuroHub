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
        Schema::create('multiple_question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('multiple_question_id');
            $table->string('value');
            $table->timestamps();
        });
        Schema::create('value_question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('value_question_id');
            $table->integer('value');
            $table->timestamps();
        });
        Schema::create('open_question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('open_question_id');
            $table->string('value');
            $table->timestamps();
        });
        Schema::create('multiple_selection_question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('multiple_selection_question_id');
            $table->json('value');
            $table->timestamps();
        });
        Schema::create('image_question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_question_id');
            $table->json('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_question_results');
        Schema::dropIfExists('value_question_results');
        Schema::dropIfExists('open_question_results');
        Schema::dropIfExists('multiple_selection_question_results');
        Schema::dropIfExists('image_question_results');
    }
};
