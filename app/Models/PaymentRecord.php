<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'student_id',
        'amount',
        'payment_method',
        'payment_channel',
        'transaction_id',
        'payment_time',
        'status',
        'refund_amount',
        'refund_reason',
        'refund_time',
        'refund_transaction_id',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'payment_time' => 'datetime',
        'refund_time' => 'datetime',
        'status' => 'string',
    ];

    /**
     * 支付记录关联的账单(多对一)
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * 支付记录关联的学生(多对一)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * 检查是否已退款
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * 检查是否退款中
     */
    public function isRefunding(): bool
    {
        return $this->status === 'refunding';
    }

    /**
     * 退款
     */
    public function refund(string $reason, string $refundTransactionId): void
    {
        $this->update([
            'status' => 'refunded',
            'refund_reason' => $reason,
            'refund_time' => now(),
            'refund_transaction_id' => $refundTransactionId,
        ]);

        // 同时更新账单状态
        if ($this->invoice) {
            $this->invoice->update(['status' => 'refunded']);
        }
    }

    /**
     * 开始退款
     */
    public function startRefund(string $reason): void
    {
        $this->update([
            'status' => 'refunding',
            'refund_reason' => $reason,
        ]);
    }
}
