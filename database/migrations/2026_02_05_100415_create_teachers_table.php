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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('关联users表ID');
            $table->foreignId('admin_user_id')->nullable()->constrained('admin_users')->nullOnDelete()->comment('关联admin_users表ID（后台管理用）');
            $table->string('name', 100)->comment('教师姓名');
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->string('email', 100)->comment('邮箱');
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('admin_user_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
