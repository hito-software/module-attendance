<?php

namespace Hito\Modules\Attendance\Http\Controllers\Admin;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Modules\Attendance\Models\AttendanceFlowBlock;
use Hito\Modules\Attendance\Services\AttendanceFlowService;
use Hito\Platform\Http\Controllers\Controller;
use Hito\Platform\Services\DepartmentService;
use Hito\Platform\Services\RoleService;
use Hito\Platform\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FlowController extends Controller
{
    private string $entitySingular = 'Flow';
    private string $entityPlural = 'Flows';

    public function __construct(private readonly AttendanceFlowService $attendanceFlowService,
                                private readonly UserService           $userService,
                                private readonly DepartmentService     $departmentService,
                                private readonly RoleService           $roleService)
    {
        $this->authorizeResource(AttendanceFlow::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        $flows = $this->attendanceFlowService->getAll();

        return AdminResourceFactory::index($flows, function (AttendanceFlow $flow) {
            return view('hito.attendance::admin.flows._index-item', compact('flow'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.attendance.flows.create'))
            ->showUrl(function (AttendanceFlow $flow) {
                if (!auth()->user()->can('show', $flow)) {
                    return null;
                }

                return route('admin.attendance.flows.show', $flow->id);
            })
            ->editUrl(function (AttendanceFlow $flow) {
                if (!auth()->user()->can('edit', $flow)) {
                    return null;
                }

                return route('admin.attendance.flows.edit', $flow->id);
            })
            ->deleteUrl(function (AttendanceFlow $flow) {
                if (!auth()->user()->can('delete', $flow)) {
                    return null;
                }

                return route('admin.attendance.flows.delete', $flow->id);
            })
            ->build();
    }

    public function create(AttendanceFlow $flow)
    {
        $flows = $this->attendanceFlowService->getAll();

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.attendance.flows.store'))
            ->view(view('hito.attendance::admin.flows._form', compact('flow','flows')))
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function store(Request $request, AttendanceFlow $flow)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('attendance_flows')->ignoreModel($flow)
            ],
            'description' => 'max:255',
        ]);

        $flow = $this->attendanceFlowService->create(request('name'), request('description'), auth()->id());

        return AdminResourceFactory::store('admin.attendance.flows.edit', $flow->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param AttendanceFlow $flow
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(AttendanceFlow $flow)
    {
        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($flow->name)
            ->view(view('hito.attendance::admin.flows._show', compact('flow')))
            ->editUrl(route('admin.attendance.flows.edit', $flow->id))
            ->deleteUrl(route('admin.attendance.flows.delete', $flow->id))
            ->indexUrl(route('admin.attendance.flows.index'))
            ->build();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AttendanceFlow $flow
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit(AttendanceFlow $flow)
    {
        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.attendance.flows.update', $flow->id))
            ->view(view('hito.attendance::admin.flows._form', compact('flow')))
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param AttendanceFlow $flow
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, AttendanceFlow $flow)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('attendance_flows')->ignoreModel($flow)
            ],
            'description' => 'max:255',
        ]);

        $this->saveFlow($flow);

        $data = request(['name', 'description']);

        if ($this->attendanceFlowService->update($flow->id, $data)) {
            return AdminResourceFactory::update()
                ->entity($this->entitySingular, $this->entityPlural)
                ->build();
        }

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->failedMessage('There has been an error while updating the data')
            ->build();
    }

    public function delete(AttendanceFlow $flow)
    {
        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.attendance.flows.destroy', $flow->id))
            ->cancelUrl(route('admin.attendance.flows.show', $flow->id))
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AttendanceFlow $flow
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(AttendanceFlow $flow)
    {
        $flow->delete();

        return AdminResourceFactory::destroy('admin.attendance.flows.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    private function saveFlow(AttendanceFlow $flow)
    {
        $blocks = request('flow.items', []);

        // Reset flow
        $flow->blocks()->delete();
        $flow->update(['condition' => request('flow.condition')]);

        $this->saveBlocks($flow, $blocks);
    }

    private function saveBlocks(AttendanceFlow $flow, array $blocks)
    {
        foreach ($blocks as $block) {
            $parent = AttendanceFlowBlock::create([
                'flow_id' => $flow->id,
                'order' => $block['order'],
                'condition' => !empty($block['condition']) ? $block['condition'] : null
            ]);

            foreach ($block['children'] as $child) {
                if (empty($child['value'])) {
                    continue;
                }

                $value = match ($child['type']) {
                    'department' => $this->departmentService->getById($child['value']),
                    'project-role', 'team-role' => $this->roleService->getById($child['value']),
                    'user' => $this->userService->getById($child['value']),
                    default => null
                };

                $data = [
                    'flow_id' => $flow->id,
                    'parent_id' => $parent->id,
                    'type' => $child['type'],
                    'value' => $child['value'],
                    'min' => $child['min'],
                    'order' => $child['order']
                ];

                if (!empty($value)) {
                    $data['model_id'] = $value->id;
                    $data['model_type'] = get_class($value);
                }

                AttendanceFlowBlock::create($data);
            }
        }
    }
}
