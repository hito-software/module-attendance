<?php

namespace Hito\Modules\Attendance\Observers;

use Hito\Modules\Attendance\Models\AttendanceRequestApproval;
use Hito\Modules\Attendance\Notifications\AttendanceRequestApprovalNotification;

class AttendanceRequestApprovalObserver
{
    public function created(AttendanceRequestApproval $attendanceRequestApproval): void
    {
        $attendanceRequestApproval->user->notify(new AttendanceRequestApprovalNotification($attendanceRequestApproval->attendanceRequest));
    }
}
