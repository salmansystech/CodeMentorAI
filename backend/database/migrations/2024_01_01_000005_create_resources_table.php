<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['tutorial', 'guide', 'exercise', 'video']);
            $table->string('category');
            $table->string('url')->nullable();
            $table->string('difficulty_level')->default('beginner');
            $table->timestamps();
            $table->index('submission_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resources');
    }
};
