<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type' => 'string',
    ];

    /**
     * 检查用户是否为教师
     */
    public function isTeacher(): bool
    {
        return $this->user_type === 'teacher';
    }

    /**
     * 检查用户是否为学生
     */
    public function isStudent(): bool
    {
        return $this->user_type === 'student';
    }

    /**
     * 获取学生详细信息
     */
    public function studentInfo(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * 获取教师详细信息
     */
    public function teacherInfo(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
}
