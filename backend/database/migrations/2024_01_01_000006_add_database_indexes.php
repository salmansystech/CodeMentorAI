<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['language', 'created_at']);
            $table->index('status');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['submission_id', 'created_at']);
            $table->index('overall_score');
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->index(['submission_id', 'category']);
            $table->index('category');
            $table->index('type');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('level');
            $table->index(['total_points', 'level']);
            $table->index('current_streak');
            $table->index('email');
        });

        Schema::table('user_badges', function (Blueprint $table) {
            $table->index(['user_id', 'badge_id']);
            $table->index('earned_at');
        });
    }

    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['language', 'created_at']);
            $table->dropIndex('status');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['submission_id', 'created_at']);
            $table->dropIndex('overall_score');
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->dropIndex(['submission_id', 'category']);
            $table->dropIndex('category');
            $table->dropIndex('type');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('level');
            $table->dropIndex(['total_points', 'level']);
            $table->dropIndex('current_streak');
            $table->dropIndex('email');
        });

        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'badge_id']);
            $table->dropIndex('earned_at');
        });
    }
};
