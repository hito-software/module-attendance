<?php


namespace Hito\Modules\Attendance\Http\Controllers;

use Carbon\Carbon;
use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Services\AttendanceFlowService;
use Hito\Modules\Attendance\Services\AttendanceRequestService;
use Hito\Modules\Attendance\Services\AttendanceTypeService;
use Hito\Platform\Http\Controllers\Controller;
use Hito\Platform\Models\ProjectMember;
use Hito\Platform\Models\TeamMember;
use Hito\Platform\Services\DepartmentService;
use Hito\Platform\Services\ProjectService;
use Hito\Platform\Services\RoleService;
use Hito\Platform\Services\TeamService;
use Hito\Platform\Services\UserService;
use Illuminate\Support\Arr;

class RequestController extends Controller
{
    private string $entitySingular = 'Request';
    private string $entityPlural = 'Requests';

    public function __construct(private readonly AttendanceTypeService    $attendanceTypeService,
                                private readonly AttendanceRequestService $attendanceRequestService,
                                private readonly AttendanceFlowService    $attendanceFlowService)
    {
        $this->authorizeResource(AttendanceRequest::class);
    }

    public function index()
    {
        $userId = auth()->user()->id;
        $myRequests = $this->attendanceRequestService->getAllPaginatedByUserId($userId, true);
        $myApprovals = $this->attendanceRequestService->getAllByApproverId($userId, true);

        return view('hito.attendance::requests.index', compact('myRequests', 'myApprovals'));
    }

    public function show(AttendanceRequest $request)
    {
        $types = $this->attendanceTypeService->getAll();
        $myApproval = $request->approvals()->whereUserId(auth()->user()->id)->get();
        $myApproval = $myApproval->count() ? $myApproval->first() : null;

        return view('hito.attendance::requests.show', compact('types', 'request', 'myApproval'));
    }

    public function create(AttendanceRequest $request)
    {
        $types = $this->attendanceTypeService->getAll()->map(fn($type) => [
            'value' => $type->id,
            'label' => $type->name
        ])->toArray();

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('attendance.requests.store'))
            ->view(view('hito.attendance::requests._form', compact('request','types')))
            ->build();
    }

    public function store()
    {
        $this->validate(request(), [
            'type' => 'required|uuid',
            'start_date' => 'required|date|after:today',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|max:255'
        ]);

        $startDate = Carbon::parse(request('start_date'));
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : null;

        if ($this->checkRequestsForInterval($startDate, $endDate)) {
            $errors = [
                'start_date' => [
                    'The selected date already has a request created'
                ],
                'end_date' => !is_null($endDate) ? [
                    'The selected date already has a request created'
                ] : []
            ];

            return back()->withErrors($errors);
        }

        $request = $this->attendanceRequestService->create(auth()->user()->id, request('type'), $startDate, $endDate,
            request('description'));

        if (is_null($request?->type?->flow)) {
            $this->attendanceRequestService->update($request->id, ['status' => 'APPROVED']);
        } else {
            $this->calculate($request);
        }

        return redirect()->route('attendance.requests.show', $request->id);
    }

    public function edit()
    {
        return view('hito.attendance::requests.edit');
    }

    public function update()
    {
        return route('hito.attendance::request.show');
    }

    public function delete()
    {
        return view('hito.attendance::requests.delete');
    }

    public function destroy()
    {
        return route('hito.attendance::request.index');
    }

    public function updateApproval(AttendanceRequest $attendanceRequest)
    {
        $data = $this->validate(request(), [
            'value' => 'required|boolean'
        ]);

        $isApproved = (bool)$data['value'];
        $attendanceRequest->approvals()->whereUserId(auth()->user()->id)->update(['is_approved' => $isApproved]);

        $approvals = $attendanceRequest->approvals()->pluck('is_approved');

        if ($approvals->filter(fn($item) => !is_null($item) && $item === false)->count()) {
            $attendanceRequest->update(['status' => 'REJECTED']);
        } elseif($approvals->count() === $approvals->filter(fn($item) => $item === true)->count()) {
            $attendanceRequest->update(['status' => 'APPROVED']);
        }

        return redirect()->back();
    }

    public function recalculate(AttendanceRequest $attendanceRequest)
    {
        $attendanceRequest->update(['status' => 'PENDING']);
        $this->calculate($attendanceRequest);

        return redirect()->back();
    }

    private function calculate(AttendanceRequest $attendanceRequest)
    {
        $type = $attendanceRequest->type;
        $flow = $type->flow;

        if (empty($flow)) {
            return;
        }

        // TODO Make diff
        $attendanceRequest->approvals()->delete();

        $users = $this->getUsersFromFlow($flow, $attendanceRequest->user_id);
        foreach ($users as $userId) {
            $this->attendanceRequestService->createApproval($attendanceRequest->id, $userId);
        }
    }

    private function getUsersFromFlow(AttendanceFlow $attendanceFlow, string $userId)
    {
        $flow = $this->attendanceFlowService->groupBlocksAndConditions($attendanceFlow);

        // Get users
        $flow['items'] = array_map(function ($block) use ($userId) {
            return [
                'condition' => $block['condition'],
                'items' => array_map(fn($item) => $this->processBlock($item, $userId), $block['items'])
            ];
        }, $flow['items']);

        // Get unavailable users
        $userIds = collect($flow['items'])->pluck('items')->flatten()->unique()->toArray();
        $unavailable = $this->getUnavailableUsers($userIds, $userId);

        // Process flow
        foreach ($flow['items'] as &$group) {
            foreach ($group['items'] as &$block) {
                $users = $block['items'];
                $block['has-available'] = true;

                if (!count($users)) {
                    $block['has-available'] = false;
                    continue;
                }

                if (count($users) <= $block['min']) {
                    $block['items'] = $users;
                    continue;
                }

                $filtered = array_filter($users, fn($user) => !in_array($user, $unavailable, true));

                if (count($filtered) < $block['min']) {
                    $needed = $block['min'] - count($filtered);
                    $forced = array_filter($users, fn($user) => !in_array($user, $filtered, true));
                    $forced = array_slice($forced, 0, $needed);
                    $block['items'] = array_merge($filtered, $forced);
                    $block['has-available'] = (bool)count($filtered);
                    continue;
                }
            }
            unset($block);

            if ($group['condition'] === 'OR') {
                $filtered = Arr::where($group['items'], fn($block) => $block['has-available']);
                $filtered = array_shift($filtered);

                if (is_null($filtered)) {
                    $filtered = $group['items'][0];
                }

                $group['items'] = [$filtered];
            }

            $users = array_map(fn($block) => $block['items'], $group['items']);
            $users = array_unique(Arr::flatten($users));
            $unavailable = array_filter($users, fn($user) => in_array($user, $unavailable, true));

            $group = [
                'items' => $users,
                'has-available' => (bool)(count($users) - count($unavailable))
            ];
        }
        unset($group);

        if ($flow['condition'] === 'OR') {
            $filtered = Arr::where($flow['items'], fn($block) => $block['has-available']);
            $filtered = array_shift($filtered);

            if (is_null($filtered)) {
                $filtered = $flow['items'][0];
            }

            $flow['items'] = [$filtered];
        }

        $users = array_map(fn($group) => $group['items'], $flow['items']);

        return array_unique(Arr::flatten($users));
    }

    private function processBlock(array $block, string $userId)
    {
        $departmentService = app(DepartmentService::class);
        $roleService = app(RoleService::class);
        $userService = app(UserService::class);
        $projectService = app(ProjectService::class);
        $teamService = app(TeamService::class);

        if (empty($block['type'])) {
            return [
                'condition' => $block['condition'],
                'items' => null
            ];
        }

        $users = null;

        switch ($block['type']) {
            case 'department':
                $users = $departmentService->getById($block['value'])->users()->pluck('user_id')
                    ->filter(fn($user) => $user !== $userId);
                $unavailable = $this->getUnavailableUsers($users->toArray());
                $users = $users->filter(fn($user) => !in_array($user, $unavailable))
                    ->take($block['min']);
                break;

            case 'project-role':
                $role = $roleService->getById($block['value']);
                $projects = $projectService->getByUserId($userId)->pluck('id');
                $users = ProjectMember::whereIn('project_id', $projects->toArray())->whereRoleId($role->id)->get()
                    ->groupBy('project_id')
                    ->pluck('*.user_id')
                    ->map(fn($users) => array_slice($users, 0, $block['min']))
                    ->flatten();

                break;

            case 'team-role':
                $role = $roleService->getById($block['value']);
                $teams = $teamService->getByUserId($userId)->pluck('id');
                $users = TeamMember::whereIn('team_id', $teams->toArray())->whereRoleId($role->id)->get()
                    ->groupBy('team_id')->pluck('*.user_id')
                    ->map(fn($users) => array_slice($users, 0, $block['min']))
                    ->flatten();

                break;

            case 'user':
                $users = collect($block['value']);
                break;
        }

        return [
            'min' => $block['min'],
            'items' => json_decode($users->toJson(), true, 512, JSON_THROW_ON_ERROR)
        ];
    }

    private function getUnavailableUsers(array $userIds, string $except = null)
    {
        $users = $this->filterUsersByAvailability($userIds, false, now()->endOfDay());

        if (is_null($except)) {
            return $users;
        }

        return array_filter($users, fn($user) => $user != $except);
    }

    private function filterUsersByAvailability(array $userIds, bool $isAvailable, Carbon $date)
    {
        $types = $this->attendanceTypeService->getAll()
            ->filter(fn($type) => $type->is_unavailable === !$isAvailable)
            ->pluck('id');

        return AttendanceRequest::whereIn('user_id', $userIds)
            ->whereIn('type_id', $types)
            ->get()
            ->filter(function ($request) use ($date) {
                if ($request->status === 'REJECTED') {
                    return false;
                }

                if (is_null($request->end_at)) {
                    return $request->start_at->format('Y-m-d') === $date->format('Y-m-d');
                }

                return $date->isBetween($request->start_at->startOfDay(), $request->end_at->endOfDay());
            })->pluck('user_id')->toArray();
    }

    private function checkRequestsForInterval(Carbon $startDate, ?Carbon $endDate): bool {
        $query = AttendanceRequest::where('user_id', auth()->user()->id);

        if (!is_null($endDate))
        {
            $query->where(function ($query) use($startDate, $endDate)  {
                $query->where(function ($query) use($startDate, $endDate) {
                    $query->where('start_at', '<=', $endDate);
                    $query->where('end_at', '>=', $startDate);
                });
    
                $query->orWhere(function ($query) use($startDate, $endDate) {
                    $query->where('start_at', '<=', $endDate);
                    $query->where('start_at', '>=', $startDate);
                });
    
                $query->orWhere(function ($query) use($startDate) {
                    $query->where('start_at', $startDate);
                });
    
                $query->orWhere(function ($query) use($endDate) {
                    $query->where('start_at', $endDate);
                });
            });
        }
        else
        {
            $query->where(function ($query) use($startDate)  {
                $query->orWhere(function ($query) use($startDate) {
                    $query->where('start_at', '<=', $startDate);
                    $query->where('end_at', '>=', $startDate);
                });
    
                $query->orWhere(function ($query) use($startDate) {
                    $query->where('start_at', $startDate);
                    $query->whereNull('end_at');
                });
            });
        }

        return !!$query->count();
    }
}
