<?php


use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard',[DashboardController::class,'index'] )
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('dashboard/categories',CategoryController::class);
