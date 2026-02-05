<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_no',
        'course_id',
        'student_id',
        'amount',
        'billing_month',
        'due_date',
        'status',
        'notes',
        'created_by',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'billing_month' => 'date',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * 账单关联的课程(多对一)
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 账单关联的学生(多对一)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * 账单的创建者(多对一)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'created_by');
    }

    /**
     * 账单的支付记录
     */
    public function paymentRecords(): HasMany
    {
        return $this->hasMany(PaymentRecord::class);
    }

    /**
     * 检查账单是否已发送
     */
    public function isSent(): bool
    {
        return !is_null($this->sent_at) && in_array($this->status, ['sent', 'pending', 'paid', 'overdue']);
    }

    /**
     * 发送账单给学生
     */
    public function send(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * 标记为已支付
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
        ]);
    }

    /**
     * 检查账单是否逾期
     */
    public function isOverdue(): bool
    {
        return in_array($this->status, ['sent', 'pending'])
            && $this->due_date
            && $this->due_date->isPast();
    }

    /**
     * 标记为逾期
     */
    public function markAsOverdue(): void
    {
        if ($this->isOverdue()) {
            $this->update(['status' => 'overdue']);
        }
    }
}
