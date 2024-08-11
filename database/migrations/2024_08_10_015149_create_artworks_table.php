<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['image', 'video']);
            $table->string('file_path')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by_teacher_id')->nullable()->constrained('teachers');
            $table->foreignId('created_by_student_id')->constrained('students');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
