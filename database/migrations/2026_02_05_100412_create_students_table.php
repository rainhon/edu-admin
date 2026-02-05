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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('关联users表ID');
            $table->string('name', 100)->comment('学生姓名');
            $table->enum('gender', ['male', 'female', 'other'])->default('other')->comment('性别');
            $table->date('birth_date')->nullable()->comment('出生日期');
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->string('parent_phone', 20)->nullable()->comment('家长联系电话');
            $table->string('email', 100)->nullable()->comment('邮箱');
            $table->string('address', 255)->nullable()->comment('家庭住址');
            $table->string('school', 100)->nullable()->comment('所在学校');
            $table->string('grade', 50)->nullable()->comment('年级');
            $table->string('emergency_contact', 100)->nullable()->comment('紧急联系人');
            $table->string('emergency_phone', 20)->nullable()->comment('紧急联系电话');
            $table->text('notes')->nullable()->comment('备注信息');
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('active')->comment('学生状态');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
