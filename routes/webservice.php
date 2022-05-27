<?php

use Illuminate\Support\Facades\Route;
use Hito\Modules\Attendance\Http\Controllers\API\FlowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('attendance')->group(function () {
    Route::get('/flows/{attendance_flow}', [FlowController::class, 'show']);
});
