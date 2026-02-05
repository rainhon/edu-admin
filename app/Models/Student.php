<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'birth_date',
        'phone',
        'parent_phone',
        'email',
        'address',
        'school',
        'grade',
        'emergency_contact',
        'emergency_phone',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'status' => 'string',
    ];

    /**
     * 关联的用户账号
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 学生选修的所有课程(多对多)
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student')
            ->withPivot('enrolled_at', 'status', 'notes')
            ->withTimestamps()
            ->wherePivot('status', 'enrolled');
    }

    /**
     * 学生关联的所有账单(一对多)
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * 学生关联的所有支付记录
     */
    public function paymentRecords(): HasMany
    {
        return $this->hasMany(PaymentRecord::class);
    }

    /**
     * 获取当前活跃的课程
     */
    public function activeCourses()
    {
        return $this->courses()->where('status', 'ongoing');
    }

    /**
     * 获取已支付的账单
     */
    public function paidInvoices()
    {
        return $this->invoices()->where('status', 'paid');
    }

    /**
     * 获取未支付的账单
     */
    public function unpaidInvoices()
    {
        return $this->invoices()->whereIn('status', ['sent', 'pending', 'overdue']);
    }
}
