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
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->string('email', 100)->comment('邮箱（用于登录）');
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('email');
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
