<?php

use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\EmployeeDocumentController;
use App\Http\Controllers\Api\V1\LeaveRequestController;
use App\Http\Controllers\Api\V1\LeaveTypeController;
use App\Http\Controllers\Api\V1\PayrollController;
use App\Http\Controllers\Api\V1\PositionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('positions', PositionController::class);
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('attendances', AttendanceController::class);
        Route::apiResource('leave-types', LeaveTypeController::class);

        Route::apiResource('leave-requests', LeaveRequestController::class);
        Route::post('leave-requests/{leave_request}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('leave-requests/{leave_request}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

        Route::apiResource('payrolls', PayrollController::class);

        Route::apiResource('employees.documents', EmployeeDocumentController::class)->shallow();
        Route::get('documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download');
    });
});
