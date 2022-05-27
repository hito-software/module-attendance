<?php

namespace Hito\Modules\Attendance\Observers;

use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Notifications\AttendanceRequestApprovedNotification;
use Hito\Modules\Attendance\Notifications\AttendanceRequestRejectedNotification;

class AttendanceRequestObserver
{
    public function updated(AttendanceRequest $attendanceRequest):void
    {
        $status = $attendanceRequest->status;

        if($status !== 'PENDING') {
            if($status === 'APPROVED') {
                $attendanceRequest->user->notify(new AttendanceRequestApprovedNotification($attendanceRequest));
                return;
            }

            if($status === 'REJECTED') {
                $attendanceRequest->user->notify(new AttendanceRequestRejectedNotification($attendanceRequest));
            }
        }
    }
}
