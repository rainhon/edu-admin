<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Student;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '学生管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Student());

        $grid->column('id', __('ID'));
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('email', __('邮箱'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Student::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('姓名'));
        $show->field('phone', __('手机号'));
        $show->field('email', __('邮箱'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Student());

        $form->text('name', __('姓名'))->required();
        $form->mobile('phone', __('手机号'));
        $form->email('email', __('邮箱'))->required();
        $form->hidden('user_id', 'user_id');

        // 创建模式：显示密码字段
        if ($form->isCreating()) {
            $form->password('password', __('初始密码'))->required();
        }
        // 编辑模式：显示密码修改
        if ($form->isEditing()) {
            $form->password('password', __('新密码'))->help('留空则不修改');
        }
        $form->ignore(['password']);

        $form->saving(function (Form $form){
            // 从 request 直接获取密码
            $password = request()->input('password');

            // 判断是否为创建模式
            if (!$form->isEditing()) {
                // 创建模式：创建 users 记录
                $name = $form->name;
                $email = $form->email;

                // 检查邮箱是否已存在于 users 表
                if (User::where('email', $email)->exists()) {
                    return $form->response()->error('邮箱已被使用');
                }

                DB::transaction(function () use ($form, $name, $email, $password) {
                    // 创建 users（用email作为登录账号）
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'user_type' => 'student',
                    ]);

                    // 设置关联ID
                    $form->user_id = $user->id;
                });

            } else {
                // 编辑模式：更新密码和email
                $student = $form->model();

                // 更新密码
                if ($password) {
                    if ($student->user) {
                        $student->user->password = Hash::make($password);
                        $student->user->save();
                    }
                }

                // 更新email
                if ($form->email && $student->user) {
                    // 检查邮箱是否被其他用户使用
                    if (User::where('email', $form->email)
                        ->where('id', '!=', $student->user_id)
                        ->exists()) {
                        return $form->response()->error('邮箱已被使用');
                    }
                    $student->user->email = $form->email;
                    $student->user->save();
                }
            }
        });

        return $form;
    }
}
