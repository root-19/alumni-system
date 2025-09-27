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
        Schema::create('user_module_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_file_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('scroll_progress')->default(0); // 0-100% scroll position
            $table->unsignedInteger('time_spent')->default(0); // seconds spent reading
            $table->unsignedTinyInteger('completion_percentage')->default(0); // 0-100% completion
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            
            // Ensure one progress record per user per module
            $table->unique(['user_id', 'training_file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_module_progress');
    }
};
