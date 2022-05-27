<?php

namespace Hito\Modules\Attendance\Repositories;

use Carbon\Carbon;
use Hito\Modules\Attendance\Models\AttendanceReport;

class AttendanceReportRepositoryImpl implements AttendanceReportRepository
{
    public function create(?array $userIds = null, ?Carbon $startDate = null, ?Carbon $endDate = null, ?array $typeIds = null): AttendanceReport
    {
        // TODO Make sure to skip creation if filter already exists

        return AttendanceReport::create([
            'users' => $userIds,
            'start_at' => $startDate,
            'end_at' => $endDate,
            'types' => $typeIds
        ]);
    }

    public function getById(string $id): AttendanceReport
    {
        return AttendanceReport::whereId($id)->firstOrFail();
    }
}
