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
        Schema::create('final_assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_assessment_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('correct_answer', ['A', 'B', 'C', 'D']); // Correct answer choice
            $table->integer('points')->default(1);
            $table->integer('order')->default(0); // For ordering questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_assessment_questions');
    }
};
