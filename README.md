# 教务管理系统

基于 Laravel 10 + Laravel-admin + Laravel Passport 的教育管理后台系统。

## 技术栈

- PHP 8.2+
- Laravel 10.x
- Laravel-admin 1.x
- Laravel Passport 11.8+
- PostgreSQL

## 数据库部署步骤

### 1. 环境配置

```bash
# 复制环境配置文件
cp .env.example .env

# 生成应用密钥
php artisan key:generate
```

编辑 `.env` 文件，配置数据库连接：

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=edu_admin
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. 安装 Laravel Passport

```bash
composer require laravel/passport:^11.8
```

### 3. 安装 Passport（创建 OAuth 表）

```bash
php artisan passport:install
```

### 4. 配置 API Guard

编辑 `config/auth.php`，在 `guards` 数组中添加：

```php
'api' => [
    'driver' => 'passport',
    'provider' => 'users',
],
```

### 5. 执行数据库迁移

```bash
php artisan migrate
```

### 6. 填充角色和权限

```bash
php artisan db:seed --class=AdminTablesSeeder
```

## 数据库表结构

### 用户认证表
- `users` - 用户表（教师和学生 API 登录）
- `admin_users` - 管理后台用户表
- `admin_roles` - 角色表
- `admin_permissions` - 权限表
- `students` - 学生详细信息
- `teachers` - 教师详细信息

### 业务表
- `courses` - 课程表
- `course_student` - 课程学生关联表
- `invoices` - 账单表
- `payment_records` - 支付记录表

### OAuth 表（Passport）
- `oauth_access_tokens`
- `oauth_auth_codes`
- `oauth_clients`
- `oauth_personal_access_clients`
- `oauth_refresh_tokens`

## 启动开发服务器

```bash
php artisan serve
```

访问后台：http://127.0.0.1:8000/admin
