<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('trainings', 'progress')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->unsignedTinyInteger('progress')->default(0)->after('certificate_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('trainings', 'progress')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->dropColumn('progress');
            });
        }
    }
};
