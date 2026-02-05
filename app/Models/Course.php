<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'course_month',
        'fee',
        'description',
        'teacher_id',
        'max_students',
        'current_students',
        'classroom',
        'schedule',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'course_month' => 'date',
        'fee' => 'decimal:2',
        'max_students' => 'integer',
        'current_students' => 'integer',
        'status' => 'string',
    ];

    /**
     * 课程所属的教师(多对一)
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * 课程的所有学生(多对多)
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot('enrolled_at', 'status', 'notes')
            ->withTimestamps()
            ->wherePivot('status', 'enrolled');
    }

    /**
     * 获取所有已选课的学生（包括退课的）
     */
    public function allEnrolledStudents(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot('enrolled_at', 'status', 'notes')
            ->withTimestamps();
    }

    /**
     * 课程关联的所有账单(一对多)
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * 检查课程是否已满
     */
    public function isFull(): bool
    {
        return $this->max_students > 0 && $this->current_students >= $this->max_students;
    }

    /**
     * 获取选课学生数量
     */
    public function getEnrolledCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * 获取活跃的学生（状态为 enrolled）
     */
    public function activeStudents()
    {
        return $this->students()->where('status', 'enrolled');
    }
}
