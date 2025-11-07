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
        Schema::create('final_assessment_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('final_assessment_questions')->onDelete('cascade');
            $table->enum('choice_letter', ['A', 'B', 'C', 'D']);
            $table->text('choice_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_assessment_choices');
    }
};
