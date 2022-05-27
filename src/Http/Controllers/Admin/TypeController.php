<?php

namespace Hito\Modules\Attendance\Http\Controllers\Admin;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Modules\Attendance\Models\AttendanceType;
use Hito\Modules\Attendance\Services\AttendanceFlowService;
use Hito\Modules\Attendance\Services\AttendanceTypeService;
use Hito\Platform\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    private string $entitySingular = 'Flow';
    private string $entityPlural = 'Flows';

    public function __construct(private readonly AttendanceTypeService $attendanceTypeService,
                                private readonly AttendanceFlowService $attendanceFlowService)
    {
        $this->authorizeResource(AttendanceType::class);
    }

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $types = $this->attendanceTypeService->getAll();

        return AdminResourceFactory::index($types, function (AttendanceType $type) {
            return view('hito.attendance::admin.types._index-item', compact('type'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.attendance.types.create'))
            ->showUrl(function (AttendanceType $type) {
                if (!auth()->user()->can('show', $type)) {
                    return null;
                }

                return route('admin.attendance.types.show', $type->id);
            })
            ->editUrl(function (AttendanceType $type) {
                if (!auth()->user()->can('edit', $type)) {
                    return null;
                }

                return route('admin.attendance.types.edit', $type->id);
            })
            ->deleteUrl(function (AttendanceType $type) {
                if (!auth()->user()->can('delete', $type)) {
                    return null;
                }

                return route('admin.attendance.types.delete', $type->id);
            })
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(AttendanceType $type)
    {
        $flows = $this->attendanceFlowService->getAll()->map(fn($flow) => [
            'value' => $flow->id,
            'label' => $flow->name
        ])->toArray();

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.attendance.types.store'))
            ->view(view('hito.attendance::admin.types._form', compact('type','flows')))
            ->build();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('attendance_types')->withoutTrashed()
            ],
             'color' => [
                'required',
                Rule::unique('attendance_types')->withoutTrashed()
            ],
            'symbol' => [
                'required',
                Rule::unique('attendance_types')->withoutTrashed()
            ],
            'description' => 'required|max:255',
            'flow' => 'nullable|uuid',
            'is_unavailable' => 'required|boolean'
        ]);

        $type = $this->attendanceTypeService->create(request('name'), request('symbol'), request('color'), request('description'), request('is_unavailable'), request('flow'), auth()->id());

        return AdminResourceFactory::store('admin.attendance.types.edit', $type->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param AttendanceType $type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(AttendanceType $type)
    {
        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($type->name)
            ->view(view('hito.attendance::admin.types._show', compact('type')))
            ->editUrl(route('admin.attendance.types.edit', $type->id))
            ->deleteUrl(route('admin.attendance.types.delete', $type->id))
            ->indexUrl(route('admin.attendance.types.index'))
            ->build();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AttendanceType $type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit(AttendanceType $type)
    {
        $flows = $this->attendanceFlowService->getAll()->map(fn($flow) => [
            'value' => $flow->id,
            'label' => $flow->name
        ])->toArray();

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.attendance.types.update', $type->id))
            ->view(view('hito.attendance::admin.types._form', compact('type', 'flows')))
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param AttendanceType $type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, AttendanceType $type)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('attendance_types')->ignoreModel($type)->withoutTrashed()
            ],
            'color' => [
                'required',
                Rule::unique('attendance_types')->ignoreModel($type)->withoutTrashed()
            ],
            'symbol' => [
                'required',
                Rule::unique('attendance_types')->ignoreModel($type)->withoutTrashed()
            ],
            'description' => 'required|max:255',
            'flow' => 'nullable|uuid',
            'unavailable' => 'boolean'
        ]);

        $data = request(['name', 'description', 'symbol', 'color']);
        $data['is_unavailable'] = request('is_unavailable');

        $data['attendance_flow_id'] = null;

        if ($flowUuid = request('flow')) {
            $flowId = $this->attendanceFlowService->getById($flowUuid)->id;
            $data['attendance_flow_id'] = $flowId;
        }

        $this->attendanceTypeService->update($type->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();

    }

    public function delete(AttendanceType $type)
    {
        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.attendance.types.destroy', $type->id))
            ->cancelUrl(route('admin.attendance.types.show', $type->id))
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AttendanceType $type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Exception
     */
    public function destroy(AttendanceType $type)
    {
        $this->attendanceTypeService->delete($type->id);

        return AdminResourceFactory::destroy('admin.attendance.types.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }
}
