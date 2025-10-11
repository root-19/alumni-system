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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_post_id')->constrained('alumni_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->nullable(); // Event title
            $table->text('description')->nullable(); // Event description
            $table->enum('status', ['attending', 'not_attending', 'maybe'])->default('attending');
            $table->timestamp('checked_in_at')->nullable(); // When they actually attended
            $table->timestamps();

            $table->unique(['alumni_post_id', 'user_id']); // Prevent duplicate attendance records
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};