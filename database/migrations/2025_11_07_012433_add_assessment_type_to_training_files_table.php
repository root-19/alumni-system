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
        Schema::table('training_files', function (Blueprint $table) {
            $table->string('assessment_type')->nullable()->after('type'); // quiz, final_assessment, or null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_files', function (Blueprint $table) {
            $table->dropColumn('assessment_type');
        });
    }
};
