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
        Schema::create('multiple_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text');
            $table->json('fields');
            $table->timestamps();
        });
        Schema::create('value_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text');
            $table->json('fields');
            $table->timestamps();
        });
        Schema::create('open_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text');
            $table->timestamps();
        });
        Schema::create('multiple_selection_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text');
            $table->json('fields');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_questions');
        Schema::dropIfExists('value_questions');
        Schema::dropIfExists('open_questions');
        Schema::dropIfExists('multiple_selection_questions');
    }
};
