<?php

namespace Hito\Modules\Attendance\Providers;

use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Models\AttendanceRequestApproval;
use Hito\Modules\Attendance\Observers\AttendanceRequestApprovalObserver;
use Hito\Modules\Attendance\Observers\AttendanceRequestObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        AttendanceRequest::observe(AttendanceRequestObserver::class);
        AttendanceRequestApproval::observe(AttendanceRequestApprovalObserver::class);
    }
}
