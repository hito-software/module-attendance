<?php

namespace Hito\Modules\Attendance\Http\Controllers;

use Hito\Platform\Http\Controllers\Controller;
use Hito\Platform\Services\UserService;
use Hito\Modules\Attendance\Services\AttendanceRequestService;
use Hito\Modules\Attendance\Services\AttendanceTypeService;
use Arr;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceTypeService $attendanceTypeService,
                                private UserService $userService,
                                private AttendanceRequestService $attendanceRequestService)
    {
    }

    public function index()
    {
        $this->authorize('attendance.view');

        $today = now();
        $startOfMonth = $today->startOfMonth();
        $endOfMonth = $startOfMonth->clone()->endOfMonth();

        $attendanceTypes = $this->attendanceTypeService->getAll();
        $attendanceRequests = $this->attendanceRequestService->filter($startOfMonth, $endOfMonth,
            status: ['PENDING', 'APPROVED']);

        $days = [];
        $eventType = ['home-office', 'medical-leave', 'present', 'leave', 'travel'];
        $users = $this->userService->getAll();

        $attendance = [];

        foreach ($eventType as $type) {
            $attendance[$type] = 0;
        }

        $i = 0;
        $currentDay = $startOfMonth->clone();
        while ($currentDay < $endOfMonth) {
            if ($currentDay->isWeekend()) {
                $currentDay->addDay();
                continue;
            }

            $day = [
                'isToday' => $currentDay->isToday(),
                'date' => $currentDay->format('Y-m-d'),
                'text' => $currentDay->format('M d'),
                'day' => ucfirst($currentDay->dayName),
                'events' => []
            ];

            foreach ($users as $user) {
                $day['events'][] = $attendanceRequests->filter(function ($event) use ($currentDay, $user) {
                    if ($event->user_id !== $user->id) {
                        return false;
                    }

                    if (is_null($event->end_at)) {
                        return $event->start_at->format('Y-m-d') === $currentDay->format('Y-m-d');
                    }

                    return $currentDay->isBetween($event->start_at, $event->end_at);
                })->first();
            }

            $days[] = $day;

            $currentDay->addDay();
        }

        return view('hito.attendance::index', compact('days', 'users', 'attendance', 'today', 'attendanceTypes'));
    }

    public function calendar()
    {
        $events = collect([
            [
                'id' => 1,
                'type' => 'home-office',
                'title' => 'Home office',
                'attendees' => ['Alex Hampu'],
                'isAllDay' => true,
                'start_at' => now()
            ],
            [
                'id' => 2,
                'type' => 'holiday',
                'title' => 'Holiday',
                'attendees' => ['Alex Hampu'],
                'isAllDay' => true,
                'start_at' => now()->addDay(),
                'end_at' => now()->addDays(3)
            ],
            [
                'id' => 3,
                'type' => 'medical-leave',
                'title' => 'Medical leave',
                'attendees' => ['Alex Hampu'],
                'isAllDay' => true,
                'start_at' => now()->addDays(-4),
                'end_at' => now()->addDays(-2)
            ]
        ])->map(function ($event) {
            $data = [
                'id' => $event['id'],
                'calendarId' => $event['type'],
                'title' => $event['title'],
                'attendees' => $event['attendees'],
                'category' => $event['isAllDay'] ? 'allday' : 'time',
                'start' => $event['start_at']
            ];

            if (!empty($event['end_at'])) {
                $data['end'] = $event['end_at'];
            }

            return $data;
        });

        $view = request('view', 'month');

        if (!in_array($view, ['month', 'week'])) {
            $view = 'month';
        }

        $calendar = [
            'usageStatistics' => false,
            'defaultView' => $view,
            'isReadOnly' => true,
            'useDetailPopup' => true,
            'calendars' => [
                [
                    'id' => 'home-office',
                    'name' => 'Home office',
                    'color' => 'green'
                ],
                [
                    'id' => 'holiday',
                    'name' => 'Holiday',
                    'color' => 'blue'
                ],
                [
                    'id' => 'medical-leave',
                    'name' => 'Medical leave',
                    'color' => 'red'
                ]
            ]
        ];

        return view('hito.attendance::calendar', compact('calendar', 'events'));
    }

}
