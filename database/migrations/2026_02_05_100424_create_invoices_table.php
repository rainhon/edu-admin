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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 50)->unique()->comment('账单编号');
            $table->foreignId('course_id')->constrained()->restrictOnDelete()->comment('课程ID');
            $table->foreignId('student_id')->constrained()->restrictOnDelete()->comment('学生ID');
            $table->decimal('amount', 10, 2)->unsigned()->default(0)->comment('账单金额');
            $table->date('billing_month')->comment('账单年月');
            $table->date('due_date')->nullable()->comment('付款截止日期');
            $table->enum('status', ['draft', 'sent', 'pending', 'paid', 'cancelled', 'refunded', 'overdue'])->default('draft')->comment('账单状态');
            $table->text('notes')->nullable()->comment('账单备注');
            $table->foreignId('created_by')->constrained('teachers')->restrictOnDelete()->comment('创建者ID(关联teachers表)');
            $table->timestamp('sent_at')->nullable()->comment('发送时间');
            $table->timestamps();

            $table->index('course_id');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
