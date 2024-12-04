<?php


use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware'=>['auth:admin,web'],
    'as'=>'dashboard.',
    'prefix'=>'admin/dashboard'

],function (){

    Route::get('profile',[ProfileController::class,'edit'])
        ->name('profile.edit');
    Route::patch('profile',[ProfileController::class,'update'])
        ->name('profile.update');
    Route::get('/',[DashboardController::class,'index'] )
        ->name('dashboard');


    Route::get('categories/trash',[CategoryController::class,'trash'])
        ->name('categories.trash');
    Route::put('categories/{category}/restore',[CategoryController::class,'restore'])
        ->name('categories.restore');
    Route::delete('categories/force-delete',[CategoryController::class,'forceDelete'])
        ->name('categories.force-delete');

    Route::resources([
        'products'=>ProductController::class,
        'categories'=>CategoryController::class,
        'roles'=>RolesController::class,
        'admins'=>AdminsController::class,
        'users' => UsersController::class,
    ]);

});

