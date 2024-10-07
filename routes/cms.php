<?php

use App\Http\Controllers\cms\DashboardController;
use App\Http\Controllers\cms\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [DashboardController::class,'dashboard'])->name('dashboard');



Route::resource("user", UserController::class);

