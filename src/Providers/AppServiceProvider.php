<?php

namespace Hito\Modules\Attendance\Providers;

use Hito\Modules\Attendance\Repositories\AttendanceFlowRepository;
use Hito\Modules\Attendance\Repositories\AttendanceFlowRepositoryImpl;
use Hito\Modules\Attendance\Repositories\AttendanceReportRepository;
use Hito\Modules\Attendance\Repositories\AttendanceReportRepositoryImpl;
use Hito\Modules\Attendance\Repositories\AttendanceRequestRepository;
use Hito\Modules\Attendance\Repositories\AttendanceRequestRepositoryImpl;
use Hito\Modules\Attendance\Repositories\AttendanceTypeRepository;
use Hito\Modules\Attendance\Repositories\AttendanceTypeRepositoryImpl;
use Hito\Modules\Attendance\Services\AttendanceFlowService;
use Hito\Modules\Attendance\Services\AttendanceFlowServiceImpl;
use Hito\Modules\Attendance\Services\AttendanceReportService;
use Hito\Modules\Attendance\Services\AttendanceReportServiceImpl;
use Hito\Modules\Attendance\Services\AttendanceRequestService;
use Hito\Modules\Attendance\Services\AttendanceRequestServiceImpl;
use Hito\Modules\Attendance\Services\AttendanceTypeService;
use Hito\Modules\Attendance\Services\AttendanceTypeServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Repositories
        AttendanceTypeRepository::class => AttendanceTypeRepositoryImpl::class,
        AttendanceFlowRepository::class => AttendanceFlowRepositoryImpl::class,
        AttendanceRequestRepository::class => AttendanceRequestRepositoryImpl::class,
        AttendanceReportRepository::class => AttendanceReportRepositoryImpl::class,

        // Services
        AttendanceTypeService::class => AttendanceTypeServiceImpl::class,
        AttendanceFlowService::class => AttendanceFlowServiceImpl::class,
        AttendanceRequestService::class => AttendanceRequestServiceImpl::class,
        AttendanceReportService::class => AttendanceReportServiceImpl::class
    ];
}
