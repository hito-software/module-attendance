<?php

namespace Hito\Modules\Attendance\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Modules\Attendance\Models\AttendanceReport;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Models\AttendanceType;
use Hito\Modules\Attendance\Policies\AttendancePolicy;
use Hito\Modules\Attendance\Policies\FlowPolicy;
use Hito\Modules\Attendance\Policies\ReportPolicy;
use Hito\Modules\Attendance\Policies\RequestPolicy;
use Hito\Modules\Attendance\Policies\TypePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        AttendanceReport::class => ReportPolicy::class,
        AttendanceType::class => TypePolicy::class,
        AttendanceFlow::class => FlowPolicy::class,
        'attendance' => AttendancePolicy::class,
        AttendanceRequest::class => RequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
