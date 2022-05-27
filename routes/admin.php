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

use Illuminate\Support\Facades\Route;
use Hito\Modules\Attendance\Http\Controllers\Admin\FlowController;
use Hito\Modules\Attendance\Http\Controllers\Admin\TypeController;

Route::prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/flows/{attendance_flow}/delete', [FlowController::class, 'delete'])->name('flows.delete');
    Route::resource('flows', FlowController::class)->parameters([
        'flows' => 'attendance_flow'
    ]);

    Route::get('/types/{attendance_type}/delete', [TypeController::class, 'delete'])->name('types.delete');
    Route::resource('types', TypeController::class)->parameters([
        'types' => 'attendance_type'
    ]);
});
