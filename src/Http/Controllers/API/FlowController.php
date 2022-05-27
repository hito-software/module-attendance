<?php

namespace Hito\Modules\Attendance\Http\Controllers\API;

use Hito\Platform\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Modules\Attendance\Services\AttendanceFlowService;

class FlowController extends Controller
{
    public function __construct(private AttendanceFlowService $attendanceFlowService)
    {
    }

    public function show(AttendanceFlow $flow)
    {
        $data = $this->attendanceFlowService->groupBlocksAndConditions($flow);

        return response()->json($data);
    }
}
