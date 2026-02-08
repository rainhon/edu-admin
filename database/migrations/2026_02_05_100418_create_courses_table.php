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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('课程名称');
            $table->date('course_month')->comment('课程年月(YYYY-MM-01)');
            $table->decimal('fee', 10, 2)->unsigned()->default(0)->comment('课程费用');
            $table->foreignId('teacher_id')->constrained('teachers')->restrictOnDelete()->comment('授课教师ID(关联teachers表)');
            $table->unsignedInteger('max_students')->default(0)->comment('最大学生数(0=不限制)');
            $table->unsignedInteger('current_students')->default(0)->comment('当前学生数');
            $table->enum('status', ['planning', 'ongoing', 'completed', 'cancelled'])->default('planning')->comment('课程状态');
            $table->timestamps();

            $table->index('teacher_id');
            $table->index('course_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
