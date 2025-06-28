<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SalaryApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\AnnouncementApiController;
use App\Http\Controllers\Api\LeaveRequestApiController;
use App\Http\Controllers\Api\NotificationApiController;

Route::prefix('api')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password/request-otp', [ForgotPasswordController::class, 'requestOtp']);
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword']);


    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', fn(Request $request) => $request->user());
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/employees/{id}', [EmployeeApiController::class, 'show']);

        // Route untuk ganti password
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::get('/attendances/status', [AttendanceApiController::class, 'checkCurrentStatus']);
        Route::post('/attendances/check-in', [AttendanceApiController::class, 'checkIn']);
        Route::post('/attendances/{attendanceId}/check-out', [AttendanceApiController::class, 'checkOut']);
        Route::get('/attendances/history', [AttendanceApiController::class, 'history']);
        Route::get('/attendances/{attendance}', [AttendanceApiController::class, 'show']);

        Route::post('/leave-requests', [LeaveRequestApiController::class, 'store']);
        Route::get('/leave-requests/history', [LeaveRequestApiController::class, 'history']);
        Route::get('/leave-requests/{leaveRequest}', [LeaveRequestApiController::class, 'show']);

        Route::get('/salaries', [SalaryApiController::class, 'index']);
        Route::get('/salaries/{salary}/generate-download-link', [SalaryApiController::class, 'generateDownloadLink']);

        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::put('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead']);
        Route::get('/notifications/unread-count', [NotificationApiController::class, 'unreadCount']);

        Route::get('/announcements/latest', [AnnouncementApiController::class, 'getLatest']);

        Route::get('/leave-summary', [ProfileApiController::class, 'getLeaveSummary']);

        Route::post('/fcm-token', [ProfileApiController::class, 'updateFcmToken']);
    });
});
Route::get('/salaries/{salary}/download', [SalaryApiController::class, 'downloadPayslip'])->name('salaries.download');
