<?php

namespace Database\Seeders;

use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 创建教师角色
        Role::firstOrCreate(
            ['slug' => 'teacher'],
            [
                'name' => '教师',
                'slug' => 'teacher',
            ]
        );

        // 系统管理员角色默认已存在(slug: administrator)
        // 这里确保它存在
        Role::firstOrCreate(
            ['slug' => 'administrator'],
            [
                'name' => '系统管理员',
                'slug' => 'administrator',
            ]
        );
    }
}
