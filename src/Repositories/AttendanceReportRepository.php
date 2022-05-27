<?php

namespace Hito\Modules\Attendance\Repositories;

use Carbon\Carbon;
use Hito\Modules\Attendance\Models\AttendanceReport;

interface AttendanceReportRepository
{
    public function create(?array $userIds = null, ?Carbon $startDate = null, ?Carbon $endDate = null,
                           ?array $typeIds = null): AttendanceReport;

    public function getById(string $id): AttendanceReport;
}
