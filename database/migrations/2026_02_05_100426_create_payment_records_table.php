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
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->restrictOnDelete()->comment('账单ID');
            $table->foreignId('student_id')->constrained()->restrictOnDelete()->comment('学生ID');
            $table->decimal('amount', 10, 2)->unsigned()->default(0)->comment('支付金额');
            $table->string('payment_method', 50)->comment('支付方式');
            $table->string('payment_channel', 50)->nullable()->comment('支付渠道');
            $table->string('transaction_id', 100)->nullable()->comment('交易流水号');
            $table->timestamp('payment_time')->comment('支付时间');
            $table->string('status', 20)->default('success')->comment('支付状态');
            $table->decimal('refund_amount', 10, 2)->unsigned()->default(0)->comment('退款金额');
            $table->string('refund_reason', 255)->nullable()->comment('退款原因');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->string('refund_transaction_id', 100)->nullable()->comment('退款交易流水号');
            $table->text('notes')->nullable()->comment('备注');
            // 添加 Omise 相关字段
            $table->string('omise_charge_id')->nullable()->after('transaction_id')->comment('Omise Charge ID');
            $table->string('omise_source_id')->nullable()->after('omise_charge_id')->comment('Omise Source ID');
            $table->json('omise_response')->nullable()->after('notes')->comment('Omise API 响应数据');
            $table->timestamps();

            $table->index('invoice_id');
            $table->index('student_id');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_records');
    }
};
