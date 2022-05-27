 @extends('hito.attendance::_layout')

@section('title', 'Attendance requests')

@section('actions')
    @can('create', \Hito\Modules\Attendance\Models\AttendanceRequest::class)
        <a href="{{ route('attendance.requests.create') }}"
        class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-plus"></i> Make request
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4">
        <div class="space-y-2 my-4 bg-white p-4 rounded-lg shadow">
            <h2 class="uppercase tracking-wide font-bold">My requests</h2>

            <div class="space-y-2">
                <div class="grid grid-cols-4 py-4 bg-gray-100 rounded font-bold">
                    <div class="px-4">Type</div>
                    <div class="px-4">Request date</div>
                    <div class="px-4">Status</div>
                    <div class="px-4">Approve count</div>
                </div>

                <div class="space-y-2">
                    @foreach($myRequests as $request)
                        <div class="relative hover:bg-blue-200 rounded">
                            <div class="grid grid-cols-4 items-center py-2 hover:bg-opacity-25 hover:bg-blue-100">
                                <div class="px-4">
                                    @include('hito.attendance::_type-pill', ['color' => $request?->type?->color,
'symbol' => $request?->type?->symbol, 'name' => $request?->type?->name])
                                </div>
                                <div class="px-4">{{ $request->created_at->format('d-m-Y H:i') }}</div>
                                <div class="px-4">
                                    @if($request->status === 'APPROVED')
                                        <span class="uppercase text-xs font-bold py-1 px-2 bg-green-600 bg-opacity-75
                                        rounded text-white inline-block cursor-default">{{ $request->status }}</span>
                                    @elseif($request->status === 'REJECTED')
                                        <span class="uppercase text-xs font-bold py-1 px-2 bg-red-600 bg-opacity-75
                                        rounded text-white inline-block cursor-default">{{ $request->status }}</span>
                                    @else
                                        <span class="uppercase text-xs font-bold py-1 px-2 bg-yellow-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->status }}</span>
                                    @endif
                                </div>
                                <div class="px-4">
                                    <span class="uppercase text-xs font-bold py-1 px-2 bg-green-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->approvals()->approved()->count() }} Approved</span>

                                    <span class="uppercase text-xs font-bold py-1 px-2 bg-red-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->approvals()->rejected()->count() }} Rejected</span>

                                    <span class="uppercase text-xs font-bold py-1 px-2 bg-blue-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->approvals->count() }} Total</span>
                                </div>
                            </div>
                            <a href="{{ route('attendance.requests.show', $request->id) }}"
                               class="absolute top-0 left-0 w-full h-full z-10 block"></a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                {{ $myRequests->links() }}
            </div>
        </div>

        <div class="hito-attendance__requests space-y-2 my-4 bg-white p-4 rounded-lg shadow">
            <h2 class="uppercase tracking-wide font-bold">Employee requests</h2>

            <div class="space-y-2">
                <div class="grid grid-cols-5 py-4 bg-gray-100 rounded font-bold">
                    <div class="px-4">Requested by</div>
                    <div class="px-4">Type</div>
                    <div class="px-4">Request date</div>
                    <div class="px-4">Status</div>
                    <div class="px-4">Approve count</div>
                </div>

                <div class="space-y-2">
                    @foreach($myApprovals as $request)
                        <div class="relative hover:bg-blue-200 rounded">
                            <div class="grid grid-cols-5 items-center py-2 hover:bg-opacity-25 hover:bg-blue-100">
                                <div class="px-4">
                                    <div class="flex">
                                        <a href="{{ route('users.show', $request->user->id) }}"
                                           class="flex items-center space-x-1 bg-gray-100 py-1 px-2 rounded z-20 hover:bg-gray-200">
                                        <span>
                                            <x-hito::UserAvatar size="2rem" :user="$request->user"/>
                                        </span>
                                            <span
                                                class="hito-attendance__requests__employee_name truncate" title="{{ $request->user->full_name }}">{{ $request->user->full_name }}</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-4">
                                    @include('hito.attendance::_type-pill', ['color' => $request->type->color,
'symbol' => $request->type->symbol, 'name' => $request->type->name])
                                </div>
                                <div class="px-4">{{ $request->created_at->format('d-m-Y H:i') }}</div>
                                <div class="px-4">
                                    @if($request->status === 'APPROVED')
                                        <span class="uppercase text-xs font-bold py-1 px-2 bg-green-600 bg-opacity-75
                                        rounded text-white inline-block cursor-default">{{ $request->status }}</span>
                                    @elseif($request->status === 'REJECTED')
                                        <span class="uppercase text-xs font-bold py-1 px-2 bg-red-600 bg-opacity-75
                                        rounded text-white inline-block cursor-default">{{ $request->status }}</span>
                                    @else
                                        <span class="uppercase text-xs font-bold py-1 px-2 bg-yellow-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->status }}</span>
                                    @endif
                                </div>
                                <div class="px-4">
                                    <span class="uppercase text-xs font-bold py-1 px-2 bg-green-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->approvals()->approved()->count() }} Approved</span>

                                    <span class="uppercase text-xs font-bold py-1 px-2 bg-red-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->approvals()->rejected()->count() }} Rejected</span>

                                    <span class="uppercase text-xs font-bold py-1 px-2 bg-blue-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">{{ $request->approvals->count() }} Total</span>
                                </div>
                            </div>
                            <a href="{{ route('attendance.requests.show', $request->id) }}"
                               class="absolute top-0 left-0 w-full h-full z-10 block"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
