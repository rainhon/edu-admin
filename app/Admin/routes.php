<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\StudentController;
use App\Admin\Controllers\TeacherController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('teachers', TeacherController::class);
    $router->resource('students', StudentController::class);
});
