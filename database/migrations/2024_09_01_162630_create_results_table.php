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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id');
            $table->foreignId('interview_id');
            $table->boolean('status')->default(0);
            $table->string('result')->nullable();
            $table->float('score')->default(0);
            $table->timestamps();
        });
        Schema::create('section_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id');
            $table->foreignId('sectionable_id');
            $table->string('sectionable_type');
            $table->boolean('status')->default(0);
            $table->integer('progressive');
            $table->string('result')->nullable();
            $table->float('score')->default(0);
            $table->boolean('jump')->default(0);
            $table->timestamps();
        });
        Schema::create('question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id');
            $table->foreignId('section_result_id');
            $table->integer('progressive');
            $table->boolean('jump')->default(0);
            $table->foreignId('questionable_id')->nullable();
            $table->string('questionable_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
        Schema::dropIfExists('section_results');
        Schema::dropIfExists('question_results');
    }
};
