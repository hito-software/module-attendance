<?php

namespace Hito\Modules\Attendance\Providers;

use Hito\Module\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Hito\Modules\Attendance\Services\AttendanceFlowService;
use Hito\Modules\Attendance\Services\AttendanceReportService;
use Hito\Modules\Attendance\Services\AttendanceRequestService;
use Hito\Modules\Attendance\Services\AttendanceTypeService;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerAdminRoutes(__DIR__ . '/../../routes/admin.php');
        $this->registerWebRoutes(__DIR__ . '/../../routes/web.php');
        $this->registerWebServiceRoutes(__DIR__ . '/../../routes/webservice.php');

        Route::bind('attendance_type', fn($value) => app(AttendanceTypeService::class)->getById($value));
        Route::bind('attendance_flow', fn($value) => app(AttendanceFlowService::class)->getById($value));
        Route::bind('attendance_request', fn($value) => app(AttendanceRequestService::class)->getById($value));
        Route::bind('attendance_report', fn($value) => app(AttendanceReportService::class)->getById($value));
    }
}
