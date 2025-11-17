<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up() {
        if (Schema::hasColumn('comments', 'post_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('post_id', 'alumni_post_id');
            });
        }
    }

    public function down() {
        if (Schema::hasColumn('comments', 'alumni_post_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('alumni_post_id', 'post_id');
            });
        }
    }
};

