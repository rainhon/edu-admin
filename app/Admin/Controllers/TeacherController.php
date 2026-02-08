<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Support\Facades\Hash;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;

class TeacherController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Teacher';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Teacher());

        $grid->column('id', 'ID');
        $grid->column('name', '姓名');
        $grid->column('phone', '手机号');
        $grid->column('email', '邮箱');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

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
        $show = new Show(Teacher::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', '姓名');
        $show->field('phone', '手机号');
        $show->field('email', '邮箱');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Teacher());

        $form->text('name', __('姓名'))->required();
        $form->mobile('phone', __('手机号'));
        $form->email('email', __('邮箱'))->required();
        $form->hidden('user_id', 'user_id');
        $form->hidden('admin_user_id', 'admin_user_id');

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
            // 从 request 直接获取密码，避免 ignore 导致无法获取
            $password = request()->input('password');

            // 判断是否为创建模式
            if (!$form->isEditing()) {
                // 创建模式：创建 admin_users 和 users
                $name = $form->name;
                $email = $form->email;

                // 检查邮箱是否已存在于 users 表
                if (User::where('email', $email)->exists()) {
                    return $form->response()->error('邮箱已被使用');
                }

                // 检查邮箱是否已存在于 admin_users 表（作为用户名）
                if (Administrator::where('username', $email)->exists()) {
                    return $form->response()->error('该邮箱已被用于后台登录账号');
                }

                DB::transaction(function () use ($form, $name, $email, $password) {
                    // 1. 创建 admin_users（使用邮箱作为用户名）
                    $adminUser = Administrator::create([
                        'username' => $email,
                        'password' => Hash::make($password),
                        'name' => $name,
                    ]);

                    // 分配教师角色
                    $teacherRole = Role::where('slug', 'teacher')->first();
                    if ($teacherRole) {
                        $adminUser->roles()->attach($teacherRole->id);
                    }

                    // 2. 创建 users
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'user_type' => 'teacher',
                    ]);

                    // 3. 设置关联ID
                    $form->admin_user_id = $adminUser->id;
                    $form->user_id = $user->id;
                });

            } else {
                // 编辑模式：更新密码和email
                $teacher = $form->model();

                // 更新密码
                if ($password) {
                    if ($teacher->adminUser) {
                        $teacher->adminUser->password = Hash::make($password);
                        $teacher->adminUser->save();
                    }
                    if ($teacher->user) {
                        $teacher->user->password = Hash::make($password);
                        $teacher->user->save();
                    }
                }

                // 更新email
                if ($form->email && $teacher->user) {
                    // 检查邮箱是否被其他用户使用
                    if (User::where('email', $form->email)
                        ->where('id', '!=', $teacher->user_id)
                        ->exists()) {
                        return $form->response()->error('邮箱已被使用');
                    }
                    $teacher->user->email = $form->email;
                    $teacher->user->save();
                }
            }
        });

        return $form;
    }
}
