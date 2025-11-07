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
        Schema::table('resumes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->string('full_name')->nullable()->after('user_id');
            $table->string('contact_number')->nullable()->after('full_name');
            $table->string('email')->nullable()->after('contact_number');
            $table->text('objective')->nullable()->after('email');
            $table->text('educational_attainment')->nullable()->after('objective');
            $table->text('training_seminars')->nullable()->after('educational_attainment');
            $table->text('work_experience')->nullable()->after('training_seminars');
            $table->string('file_name')->nullable()->change();
            $table->string('file_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'full_name',
                'contact_number',
                'email',
                'objective',
                'educational_attainment',
                'training_seminars',
                'work_experience'
            ]);
        });
    }
};
