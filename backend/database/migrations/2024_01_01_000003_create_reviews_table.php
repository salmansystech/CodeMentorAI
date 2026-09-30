<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->json('findings');
            $table->integer('overall_score')->default(50);
            $table->integer('bugs_count')->default(0);
            $table->integer('style_issues_count')->default(0);
            $table->integer('performance_issues_count')->default(0);
            $table->integer('security_issues_count')->default(0);
            $table->timestamps();
            $table->index('submission_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
