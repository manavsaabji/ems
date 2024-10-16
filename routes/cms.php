<?php

use App\Http\Controllers\cms\ActivityLogsController;
use App\Http\Controllers\cms\DashboardController;
use App\Http\Controllers\cms\EmployeeController;
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
Route::resource("employee", EmployeeController::class);

//Activity Logs
Route::get('activity-logs',                 [ActivityLogsController::class, 'index'])->name('activityLogs');

Route::get('assign-role/{id}', [UserController::class, 'assignRole'])->name('assignRole');
Route::post('submit-role/', [UserController::class, 'submitRole'])->name('submitRole');

Route::get('assign-permission/{id}', [RoleController::class, 'assignPermission'])->name('assignPermission');
Route::post('submit-permission/', [RoleController::class, 'submitPermission'])->name('submitPermission');


Route::get('edit-profile/', [UserController::class, 'editProfile'])->name('editProfile');
Route::put('submit-profile/', [UserController::class, 'submitProfile'])->name('submitProfile');



