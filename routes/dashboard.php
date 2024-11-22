<?php


use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware'=>['auth','auth.type:super-admin,admin'],
    'as'=>'dashboard.',
    'prefix'=>'dashboard'

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

    Route::resource('/categories',CategoryController::class);

    Route::resource('/products',ProductController::class);
});

