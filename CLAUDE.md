# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

这是一个基于 **Laravel 10** 和 **laravel-admin** 的教育后台管理系统 (edu-admin)。

## 核心架构

### Laravel-admin 后台管理框架

项目使用 `encore/laravel-admin` 作为后台管理基础框架，主要文件结构：

- **[app/Admin/](app/Admin/)** - 后台管理核心目录
  - `bootstrap.php` - Laravel-admin 启动配置文件，用于移除内置表单字段（map、editor）或扩展自定义字段
  - `routes.php` - 后台路由定义，使用路由组配置前缀、命名空间和中间件
  - `Controllers/` - 后台控制器目录
    - `AuthController.php` - 认证控制器
    - `HomeController.php` - 首页控制器
    - `ExampleController.php` - 示例控制器

- **[config/admin.php](config/admin.php)** - Laravel-admin 配置文件，包含名称、Logo、路由前缀等设置

### 目录结构

```
app/
├── Admin/              # Laravel-admin 后台管理
│   ├── Controllers/    # 后台控制器
│   ├── bootstrap.php   # 启动配置
│   └── routes.php      # 后台路由
├── Http/               # 标准 Laravel HTTP 层
│   ├── Controllers/    # 前台控制器
│   ├── Middleware/     # 中间件
│   └── Kernel.php      # HTTP 内核
├── Models/             # Eloquent 模型
└── Providers/          # 服务提供者

database/
└── migrations/         # 数据库迁移文件
    └── 2016_01_04_173148_create_admin_tables.php  # laravel-admin 表结构

routes/
├── web.php             # Web 路由
├── api.php             # API 路由
├── channels.php        # 广播频道
└── console.php         # 闭包命令路由
```

## 常用命令

### 开发服务器
```powershell
php artisan serve
```

### 前端资源构建
```powershell
# 开发模式（支持热更新）
npm run dev

# 生产构建
npm run build
```

### 数据库操作
```powershell
# 运行数据库迁移
php artisan migrate

# 回滚最后一次迁移
php artisan migrate:rollback

# 创建新的迁移文件
php artisan make:migration create_table_name

# 数据库填充
php artisan db:seed
```

### Laravel-admin 相关
```powershell
# 安装/发布 laravel-admin
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"

# 创建管理员用户
php artisan admin:create-user

# 重新生成 Laravel Admin 权限
php artisan admin:reset-password
```

### 测试
```powershell
# 运行所有测试
./vendor/bin/phpunit

# 运行特定测试套件
./vendor/bin/phpunit --testsuite=Unit
./vendor/bin/phpunit --testsuite=Feature

# 运行单个测试文件
./vendor/bin/phpunit tests/Feature/ExampleTest.php
```

### Composer 依赖管理
```powershell
# 安装依赖
composer install

# 更新依赖
composer update

# 自动加载重新生成
composer dump-autoload
```

### 代码质量
```powershell
# Laravel Pint 代码格式化
./vendor/bin/pint

# 检查代码风格但不修改
./vendor/bin/pint --test
```

### 其他常用 Artisan 命令
```powershell
# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 生成应用密钥
php artisan key:generate

# 创建控制器
php artisan make:controller ControllerName

# 创建模型
php artisan make:model ModelName

# 查看路由列表
php artisan route:list
```

## Laravel-admin 路由配置

后台路由在 [app/Admin/routes.php](app/Admin/routes.php) 中定义，使用以下配置：

- **前缀**: 通过 `config('admin.route.prefix')` 配置（默认为 `admin`）
- **命名空间**: 通过 `config('admin.route.namespace')` 配置（默认为 `App\Admin\Controllers`）
- **中间件**: 通过 `config('admin.route.middleware')` 配置

所有后台路由都会自动应用 `web` 中间件组和 Laravel-admin 的认证中间件。

## Laravel-admin 自定义

在 [app/Admin/bootstrap.php](app/Admin/bootstrap.php) 中可以：

1. **移除内置表单字段**: `Encore\Admin\Form::forget(['map', 'editor'])`
2. **扩展自定义表单字段**: `Encore\Admin\Form::extend('php', PHPEditor::class)`
3. **引入 CSS/JS 资源**: `Admin::css()`, `Admin::js()`

## 环境配置

- 确保已复制 `.env.example` 到 `.env` 并配置数据库连接
- 运行 `php artisan key:generate` 生成应用密钥
- 确保 `storage` 和 `bootstrap/cache` 目录可写

## 数据库

项目使用以下数据表（通过迁移文件）：

- `users` - 用户表
- `admin_users` - 管理员用户表（laravel-admin）
- `admin_roles` - 角色表
- `admin_permissions` - 权限表
- `admin_menu` - 菜单表
- `admin_operation_log` - 操作日志表
