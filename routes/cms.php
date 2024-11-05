<?php

use App\Http\Controllers\cms\ActivityLogsController;
use App\Http\Controllers\cms\AttendanceController;
use App\Http\Controllers\cms\DashboardController;
use App\Http\Controllers\cms\DepartmentController;
use App\Http\Controllers\cms\EmployeeController;
use App\Http\Controllers\cms\LeaveController;
use App\Http\Controllers\cms\ModuleController;
use App\Http\Controllers\cms\PermissionController;
use App\Http\Controllers\cms\RoleController;
use App\Http\Controllers\cms\TaskController;
use App\Http\Controllers\cms\TestController;
use App\Http\Controllers\cms\UserController;
use Illuminate\Support\Facades\Route;





Route::get('/dashboard', [DashboardController::class,'dashboard'])->name('dashboard');



Route::resource("user", UserController::class);
Route::resource("role", RoleController::class);

Route::resource("permission", PermissionController::class);
Route::resource("module", ModuleController::class);
Route::resource("employee", EmployeeController::class);

Route::resource("department", DepartmentController::class);
Route::resource("task", TaskController::class);
Route::resource("leave", LeaveController::class);
Route::resource("attendance", AttendanceController::class);

//Activity Logs
Route::get('activity-logs',                 [ActivityLogsController::class, 'index'])->name('activityLogs');
Route::get('leave-all-index',                 [LeaveController::class, 'leaveIndexAll'])->name('leaveIndexAll');
Route::get('assign-role/{id}', [UserController::class, 'assignRole'])->name('assignRole');
Route::post('submit-role/', [UserController::class, 'submitRole'])->name('submitRole');

Route::get('assign-permission/{id}', [RoleController::class, 'assignPermission'])->name('assignPermission');
Route::post('submit-permission/', [RoleController::class, 'submitPermission'])->name('submitPermission');


Route::get('edit-profile/', [UserController::class, 'editProfile'])->name('editProfile');
Route::put('submit-profile/', [UserController::class, 'submitProfile'])->name('submitProfile');

Route::post('leave-cancel/{id}', [LeaveController::class, 'leaveCancel'])->name('leaveCancel');

Route::get('attendance-first-date', [AttendanceController::class, 'getFirstDate'])->name('getFirstDate');

Route::get('manually-attendance', [AttendanceController::class, 'manuallyAttendance'])->name('manuallyAttendance');

Route::get('get-manually-attendance', [AttendanceController::class, 'getManuallyAttendance'])->name('getManuallyAttendance');

Route::post('submit-manually-attendance/', [AttendanceController::class, 'submitManuallyAttendance'])->name('submitManuallyAttendance');

