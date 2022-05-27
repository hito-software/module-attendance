<?php

namespace Hito\Modules\Attendance\Services;

use Carbon\Carbon;
use Hito\Modules\Attendance\Models\AttendanceReport;
use Hito\Modules\Attendance\Repositories\AttendanceReportRepository;

class AttendanceReportServiceImpl implements AttendanceReportService
{
    public function __construct(private AttendanceReportRepository $attendanceReportRepository)
    {
    }

    public function create(?array $userIds = null, ?Carbon $startDate = null, ?Carbon $endDate = null, ?array $typeIds = null): AttendanceReport
    {
        return $this->attendanceReportRepository->create($userIds, $startDate, $endDate, $typeIds);
    }

    public function getById(string $id): AttendanceReport
    {
        return $this->attendanceReportRepository->getById($id);
    }
}
