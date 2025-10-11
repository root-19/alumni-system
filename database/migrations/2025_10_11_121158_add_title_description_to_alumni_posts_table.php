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
        Schema::table('alumni_posts', function (Blueprint $table) {
            $table->string('title')->nullable()->after('content');
            $table->text('description')->nullable()->after('title');
            $table->timestamp('event_date')->nullable()->after('description');
            $table->string('location')->nullable()->after('event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni_posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'event_date', 'location']);
        });
    }
};