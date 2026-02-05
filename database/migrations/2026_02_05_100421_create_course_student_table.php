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
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete()->comment('课程ID');
            $table->foreignId('student_id')->constrained()->cascadeOnDelete()->comment('学生ID');
            $table->timestamp('enrolled_at')->nullable()->comment('选课时间');
            $table->enum('status', ['enrolled', 'completed', 'dropped'])->default('enrolled')->comment('选课状态');
            $table->text('notes')->nullable()->comment('备注');
            $table->timestamps();

            $table->unique(['course_id', 'student_id'], 'uk_course_student');
            $table->index('course_id');
            $table->index('student_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
