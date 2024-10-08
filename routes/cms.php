<?php

use App\Http\Controllers\cms\ActivityLogsController;
use App\Http\Controllers\cms\DashboardController;
use App\Http\Controllers\cms\ModuleController;
use App\Http\Controllers\cms\PermissionController;
use App\Http\Controllers\cms\RoleController;
use App\Http\Controllers\cms\UserController;
use Illuminate\Support\Facades\Route;




Route::get('/dashboard', [DashboardController::class,'dashboard'])->name('dashboard');



Route::resource("user", UserController::class);
Route::resource("role", RoleController::class);
Route::resource("permission", PermissionController::class);
Route::resource("module", ModuleController::class);

//Activity Logs
Route::get('activity-logs',                 [ActivityLogsController::class, 'index'])->name('activityLogs');
