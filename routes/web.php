<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Hito\Modules\Attendance\Http\Controllers\AttendanceController;
use Hito\Modules\Attendance\Http\Controllers\ReportController;
use Hito\Modules\Attendance\Http\Controllers\RequestController;
use Hito\Modules\Attendance\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->name('attendance.')->prefix('attendance')->group(static function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');

    Route::post('/requests/{attendance_request}/recalculate', [RequestController::class, 'recalculate'])
        ->name('requests.recalculate');
    Route::resource('requests', RequestController::class)->parameters([
        'requests' => 'attendance_request'
    ]);

    Route::post('/requests/{attendance_request}/approval', [RequestController::class, 'updateApproval'])
        ->name('requests.update-approval');

    Route::prefix('reports')->name('reports.')->group(static function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/{attendance_report}', [ReportController::class, 'show'])->name('show');
        Route::get('/{attendance_report}/download', [ReportController::class, 'download'])->name('download');
    });

    Route::resource('shift', ShiftController::class)->only(['index', 'store']);
});
