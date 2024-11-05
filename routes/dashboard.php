<?php


use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware'=>['auth'],
    'as'=>'dashboard.',
    'prefix'=>'dashboard'

],function (){
    Route::get('/',[DashboardController::class,'index'] )
        ->name('dashboard');


    Route::get('categories/trash',[CategoryController::class,'trash'])
        ->name('categories.trash');
    Route::put('categories/{category}/restore',[CategoryController::class,'restore'])
        ->name('categories.restore');
    Route::delete('categories/force-delete',[CategoryController::class,'forceDelete'])
        ->name('categories.force-delete');

    Route::resource('/categories',CategoryController::class);


});

