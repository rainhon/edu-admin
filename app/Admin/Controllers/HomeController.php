<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        // 获取当前登录用户
        $currentUser = auth('admin')->user();

        // 统计教师和学生人数
        $teacherCount = Teacher::count();
        $studentCount = Student::count();

        return $content
            ->title('系统首页')
            ->description('欢迎回来')
            ->row('<div class="alert alert-info">
                <h4><i class="fa fa-bullhorn"></i> 欢迎使用教育管理系统</h4>
                <p><strong>' . e($currentUser->name) . '</strong>，欢迎您回来！今天是 ' . date('Y年m月d日') . '</p>
            </div>')
            ->row(function ($row) use ($teacherCount, $studentCount) {
                // 教师统计卡片
                $row->column(6, function ($column) use ($teacherCount) {
                    $infoBox = new InfoBox('教师人数', 'users', 'aqua', '/admin/teachers', $teacherCount);
                    $column->append($infoBox->render());
                });

                // 学生统计卡片
                $row->column(6, function ($column) use ($studentCount) {
                    $infoBox = new InfoBox('学生人数', 'user', 'green', '/admin/students', $studentCount);
                    $column->append($infoBox->render());
                });
            });
    }
}
