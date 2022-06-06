 @extends('hito.attendance::_layout')

@section('title', 'Attendance requests')

@section('actions')
    @can('create', \Hito\Modules\Attendance\Models\AttendanceRequest::class)
        <a href="{{ route('attendance.requests.create') }}"
        class="hito-attendance__actions__create">
            <i class="fas fa-plus"></i> Make request
        </a>
    @endcan
@endsection

@section('content')
    <div class="hito-attendance__user-requests__wrapper">
        <div class="hito-attendance__user-requests__wrapper hito-attendance__user-requests__legend-bg">
            <h2 class="hito-attendance__user-requests__title">My requests</h2>

            <div class="hito-attendance__user-requests__wrapper-space">
                <div class="hito-attendance__user-requests__grids">
                    <div class="hito-attendance__user-requests__rows">Type</div>
                    <div class="hito-attendance__user-requests__rows">Request date</div>
                    <div class="hito-attendance__user-requests__rows">Status</div>
                    <div class="hito-attendance__user-requests__rows">Approve count</div>
                </div>

                <div class="hito-attendance__user-requests__wraper-space">
                    @foreach($myRequests as $request)
                        <div class="hito-attendance__user-requests__request-row">
                            <div class="hito-attendance__user-requests__grid-col">
                                <div class="hito-attendance__user-requests__rows">
                                    @include('hito.attendance::_type-pill', ['color' => $request?->type?->color,
'symbol' => $request?->type?->symbol, 'name' => $request?->type?->name])
                                </div>
                                <div class="hito-attendance__user-requests__rows">{{ $request->created_at->format('d-m-Y H:i') }}</div>
                                <div class="hito-attendance__user-requests__rows">
                                    @if($request->status === 'APPROVED')
                                        <span class="hito-attendance__user-requests__status">{{ $request->status }}</span>
                                    @elseif($request->status === 'REJECTED')
                                        <span class="hito-attendance__user-requests__status">{{ $request->status }}</span>
                                    @else
                                        <span class="hito-attendance__user-requests__status">{{ $request->status }}</span>
                                    @endif
                                </div>
                                <div class="hito-attendance__user-requests__rows">
                                    <span class="hito-attendance__user-requests__status">{{ $request->approvals()->approved()->count() }} Approved</span>

                                    <span class="hito-attendance__user-requests__status-count">{{ $request->approvals()->rejected()->count() }} Rejected</span>

                                    <span class="hito-attendance__user-requests__status">{{ $request->approvals->count() }} Total</span>
                                </div>
                            </div>
                            <a href="{{ route('attendance.requests.show', $request->id) }}"
                               class="hito-attendance__user-requests__id"></a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                {{ $myRequests->links() }}
            </div>
        </div>

        <div class="hito-attendance__user-requests__title-bg">
            <h2 class="hito-attendance__user-requests__title-style">Employee requests</h2>

            <div class="hito-attendance__user-requests__space">
                <div class="hito-attendance__user-requests__grid-employee">
                    <div class="hito-attendance__user-requests__rows">Requested by</div>
                    <div class="hito-attendance__user-requests__rows">Type</div>
                    <div class="hito-attendance__user-requests__rows">Request date</div>
                    <div class="hito-attendance__user-requests__rows">Status</div>
                    <div class="hito-attendance__user-requests__rows">Approve count</div>
                </div>

                <div class="hito-attendance__user-requests__space">
                    @foreach($myApprovals as $request)
                        <div class="hito-attendance__user-requests__request-row">
                            <div class="hito-attendance__user-requests__grid-user">
                                <div class="hito-attendance__user-requests__rows">
                                    <div class="flex">
                                        <a href="{{ route('users.show', $request->user->id) }}"
                                           class="hito-attendance__user-requests__flex-user">
                                        <span>
                                            <x-hito::UserAvatar size="2rem" :user="$request->user"/>
                                        </span>
                                            <span
                                                class="hito-attendance__requests__employee_name truncate" title="{{ $request->user->full_name }}">{{ $request->user->full_name }}</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="hito-attendance__user-requests__rows">
                                    @include('hito.attendance::_type-pill', ['color' => $request->type->color,
'symbol' => $request->type->symbol, 'name' => $request->type->name])
                                </div>
                                <div class="hito-attendance__user-requests__rows">{{ $request->created_at->format('d-m-Y H:i') }}</div>
                                <div class="hito-attendance__user-requests__rows">
                                    @if($request->status === 'APPROVED')
                                        <span class="hito-attendance__user-requests__status">{{ $request->status }}</span>
                                    @elseif($request->status === 'REJECTED')
                                        <span class="hito-attendance__user-requests__status-count">{{ $request->status }}</span>
                                    @else
                                        <span class="hito-attendance__user-requests__status-pending">{{ $request->status }}</span>
                                    @endif
                                </div>
                                <div class="hito-attendance__user-requests__rows">
                                    <span class="hito-attendance__user-requests__status">{{ $request->approvals()->approved()->count() }} Approved</span>

                                    <span class="hito-attendance__user-requests__status-count">{{ $request->approvals()->rejected()->count() }} Rejected</span>

                                    <span class="hito-attendance__user-requests__status-total">{{ $request->approvals->count() }} Total</span>
                                </div>
                            </div>
                            <a href="{{ route('attendance.requests.show', $request->id) }}"
                               class="hito-attendance__user-requests__id"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
