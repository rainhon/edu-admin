<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'admin_user_id',
        'name',
        'gender',
        'phone',
        'email',
        'subject',
        'qualification',
        'hire_date',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hire_date' => 'date',
        'status' => 'string',
    ];

    /**
     * 关联的用户账号(API登录用)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 关联的后台管理账号(后台登录用)
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'admin_user_id');
    }

    /**
     * 教师创建的所有课程
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * 教师创建的所有账单
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    /**
     * 获取活跃的课程
     */
    public function activeCourses()
    {
        return $this->courses()->whereIn('status', ['planning', 'ongoing']);
    }

    /**
     * 统计教师的学生数量
     */
    public function studentsCount(): int
    {
        return $this->activeCourses()
            ->withCount('students')
            ->get()
            ->sum('students_count');
    }
}
