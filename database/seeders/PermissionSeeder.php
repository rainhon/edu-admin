<?php

namespace Database\Seeders;

use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 创建学生管理权限
        $this->createPermissions('students', '学生管理');
        $this->createPermissions('teachers', '教师管理');
        $this->createPermissions('courses', '课程管理');
        $this->createPermissions('invoices', '账单管理');

        // 为系统管理员分配所有权限
        $adminRole = Role::where('slug', 'administrator')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::pluck('id')->toArray());
        }

        // 为教师角色分配权限（不含教师管理权限）
        $teacherRole = Role::where('slug', 'teacher')->first();
        if ($teacherRole) {
            $teacherPermissions = Permission::where('slug', 'like', 'students.%')
                ->orWhere('slug', 'like', 'courses.%')
                ->orWhere('slug', 'like', 'invoices.%')
                ->get()
                ->pluck('id')
                ->toArray();

            $teacherRole->permissions()->sync($teacherPermissions);
        }
    }

    /**
     * 创建资源的CRUD权限
     */
    private function createPermissions($resource, $name): void
    {
        $permissions = [
            [
                'name' => "查看{$name}",
                'slug' => "{$resource}.view",
                'http_path' => null,
                'http_method' => null,
            ],
            [
                'name' => "创建{$name}",
                'slug' => "{$resource}.create",
                'http_path' => null,
                'http_method' => null,
            ],
            [
                'name' => "编辑{$name}",
                'slug' => "{$resource}.edit",
                'http_path' => null,
                'http_method' => null,
            ],
            [
                'name' => "删除{$name}",
                'slug' => "{$resource}.delete",
                'http_path' => null,
                'http_method' => null,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'http_path' => $permission['http_path'],
                    'http_method' => $permission['http_method'],
                ]
            );
        }
    }
}
