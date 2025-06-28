<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\SalaryController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\ActivityLogController;
use App\Http\Controllers\Web\AnnouncementController;
use App\Http\Controllers\Web\LeaveRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk Karyawan
    Route::resource('employees', EmployeeController::class);
    Route::put('/employees/{employee}/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.update-status');

    // Rute untuk Kehadiran
    Route::resource('attendances', AttendanceController::class);

    // Rute untuk Pengajuan Cuti
    Route::resource('leave-requests', LeaveRequestController::class)
        ->only(['index', 'destroy']);

    // Rute untuk update status cuti
    Route::put('/leave-requests/{leaveRequest}/update-status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update.status');

    // Rute untuk penggajian 
    Route::resource('salaries', SalaryController::class)->except(['show', 'create', 'edit']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');

    Route::resource('announcements', AnnouncementController::class)->except(['show', 'create', 'edit']);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/export/attendance', [ReportController::class, 'exportAttendance'])->name('export.attendance');
        // DITAMBAHKAN: Rute untuk ekspor cuti dan gaji
        Route::post('/export/leave', [ReportController::class, 'exportLeave'])->name('export.leave');
        Route::post('/export/salary', [ReportController::class, 'exportSalary'])->name('export.salary');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });

    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
