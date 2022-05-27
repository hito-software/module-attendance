<?php

namespace Hito\Modules\Attendance\Http\Controllers;

use Carbon\Carbon;
use Hito\Platform\Http\Controllers\Controller;

class ShiftController extends Controller
{
    public function index()
    {
        $date = request('date');

        if(empty($date)) {
            $startOfCurrentWeek = now()->startOf('week');

            return redirect()->route('attendance.shift.index', ['date' => $startOfCurrentWeek->format('Y-m-d')]);
        }

        $date = Carbon::parse($date)?->startOf('week')?->format('Y-m-d');

        return view('hito.attendance::shift.index', compact('date'));
    }
}
