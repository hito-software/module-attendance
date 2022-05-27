<?php


namespace Hito\Modules\Attendance\Http\Controllers;

use Hito\Platform\Http\Controllers\Controller;
use Hito\Platform\Services\UserService;
use Carbon\Carbon;
use Hito\Modules\Attendance\Models\AttendanceReport;
use Hito\Modules\Attendance\Services\AttendanceReportService;
use Hito\Modules\Attendance\Services\AttendanceRequestService;
use Hito\Modules\Attendance\Services\AttendanceTypeService;
use Modules\FileExporter\Facades\FileExport;

class ReportController extends Controller
{
    public function __construct(private UserService              $userService,
                                private AttendanceRequestService $attendanceRequestService,
                                private AttendanceTypeService    $attendanceTypeService,
                                private AttendanceReportService  $attendanceReportService)
    {
       $this->authorizeResource(AttendanceReport::class);
    }

    public function index()
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => $user->name
        ])->toArray();
        
        $types = $this->attendanceTypeService->getAll()->map(fn($type) => [
            'value' => $type->id,
            'label' => $type->name
        ])->toArray();
    
        return view('hito.attendance::reports.index', compact('users', 'types'));
    }

    public function store()
    {
        $this->validate(request(), [
            'start_date' => 'required|date'
        ]);

        $users = null;
        $types = null;
        $endDate = null;

        $startDate = Carbon::parse(request('start_date'));

        if (!empty(request('types'))) {
            $types = request('types');
        }

        if (!empty(request('users'))) {
            $users = request('users');
        }

        if (!empty(request('end_date'))) {
            $endDate = Carbon::parse(request('end_date'));
        }

        $report = $this->attendanceReportService->create($users, $startDate, $endDate, $types);
        return redirect()->route('attendance.reports.show', $report->id);
    }

    public function show(AttendanceReport $report)
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => $user->name
        ])->toArray();

        $types = $this->attendanceTypeService->getAll()->map(fn($type) => [
            'value' => $type->id,
            'label' => $type->name
        ])->toArray();

        $requests = $this->attendanceRequestService->filter($report->start_at, $report->end_at, $report->users,
            $report->types, ['APPROVED']);

        return view('hito.attendance::reports.index', compact('users', 'types', 'requests', 'report'));
    }

    public function download(AttendanceReport $report)
    {
        $requests = $this->attendanceRequestService->filter($report->start_at, $report->end_at, $report->users,
            $report->types, ['APPROVED']);

        FileExport::getPdf(view('hito.attendance::reports.pdf', compact('report', 'requests'))->render(), 'attendance-report.pdf');
    }
}
