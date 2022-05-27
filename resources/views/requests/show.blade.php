@extends('hito.attendance::_layout')

@section('title', 'Leave request')

@section('actions')
    <form action="{{ route('attendance.requests.recalculate', $request->id) }}" method="post">
        @csrf
        <button type="submit" data-turbo="false"
                class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-file-invoice"></i> Recalculate
        </button>
    </form>
@endsection

@section('content')
    <div class="my-4 bg-white p-4 rounded-lg shadow">
        <div class="grid md:grid-cols-6 gap-4">
            <div class="col-span-4">
                <div class="space-y-4">
                    <div class="border rounded p-2 space-y-2">
                        <label class="block font-bold">Requested by</label>
                        <div><strong>{{ $request->user->full_name }}</strong></div>
                    </div>

                    <div class="border rounded p-2 space-y-2">
                        <label class="block font-bold">Type</label>
                        <div>
                            @include('hito.attendance::_type-pill', ['color' => $request->type->color,
'symbol' => $request->type->symbol, 'name' => $request->type->name])
                        </div>
                    </div>

                    @if(!empty($request->description))
                        <div class="border rounded p-2 space-y-2">
                            <label for="form_description" class="block font-bold">Description</label>
                            <div>
                                <p>{{ $request->description }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="text-center">
                        <span class="font-bold text-xl">
                            @if(isset($request->end_at) && ($request->start_at->format('F') != $request->end_at->format('F')))
                                {{ $request->start_at->format('F') }} - {{ $request->end_at->format('F Y') }}
                            @else
                                {{ $request->start_at->format('F Y') }}
                            @endif
                        </span>
                    </div>

                    <div class="relative">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-span-2">
                <div class="border rounded p-4 space-y-2">
                    <div class="border-b-2 pb-2">
                        <div class="space-y-2">
                            <div class="flex-1">
                                <label class="block text-2xl mb">Approvals</label>
                            </div>
                            @if(!empty($myApproval))
                                <div>
                                    @if(is_null($myApproval->is_approved))
                                        <form action="{{ route('attendance.requests.update-approval', $request->id) }}"
                                              method="post">
                                            @csrf
                                            <div class="flex flex-col space-y-2">
                                                <button type="submit" name="value" value="0"
                                                        class="py-2 px-4 rounded bg-red-500 text-white uppercase text-sm font-bold hover:bg-red-400">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                                <button type="submit" name="value" value="1"
                                                        class="py-2 px-4 rounded bg-green-500 text-white uppercase text-sm font-bold hover:bg-green-400">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </div>
                                        </form>
                                    @elseif($myApproval->is_approved)
                                        <div class="py-2 px-4 rounded bg-green-500 text-white uppercase text-sm font-bold">
                                            <i class="fas fa-check"></i> You approved
                                        </div>
                                    @else
                                        <div class="py-2 px-4 rounded bg-red-500 text-white uppercase text-sm font-bold">
                                            <i class="fas fa-times"></i> You rejected
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            @foreach($request->approvals as $approval)
                                <div class="flex w-full space-x-4 items-center">
                                    <div class="relative">
                                        <x-hito::UserAvatar size="2.5rem" :user="$approval->user"/>
                                        @if(!is_null($approval->is_approved))
                                            <div
                                                    class="absolute bottom-0 right-0 transform translate-x-1 translate-y-1">
                                                @if($approval->is_approved)
                                                    <div
                                                            class="rounded-full bg-green-500 flex items-center justify-center text-white"
                                                            style="width: 20px; height: 20px;">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                @else
                                                    <div
                                                            class="rounded-full bg-red-500 flex items-center justify-center text-white"
                                                            style="width: 20px; height: 20px;">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="overflow-auto flex-1">
                                        <div class="flex items-center space-x-2">
                                            <div class="font-bold">
                                                {{ $approval->user->full_name }}
                                            </div>
                                            @if(auth()->user()->id === $approval->user->id)
                                                <div>
                                            <span class="uppercase text-xs font-bold py-1 px-2 bg-blue-600 bg-opacity-75
                                rounded text-white inline-block cursor-default">Me</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="overflow-ellipsis overflow-hidden w-full"
                                             title="{{ $approval->user->email }}">{{ $approval->user->email }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                const calendar = new Calendar('#calendar', {
                    defaultView: 'month',
                    calendars: [
                            @foreach($types as $type)
                        {
                            id: '{{ $type['uuid'] }}',
                            name: '({{ $type['symbol'] }}) {{ $type['name'] }}',
                            bgColor: '{{ $type['color'] }}',
                            color: '#fff'
                        },
                        @endforeach
                    ],
                    isReadOnly: true
                });

                calendar.createSchedules([
                    {
                        calendarId: '{{ $request->type->id }}',
                        title: '({{ $request->type->symbol }}) {{ $request->type->name }}',
                        isAllDay: true,
                        category: 'allday',
                        start: '{{ $request->start_at->format('Y-m-d') }}',
                        @if(!empty($request->end_at))
                        end: '{{ $request->end_at->format('Y-m-d') }}'
                        @endif
                    }
                ]);
            }, {
                once: true
            });
        })();
    </script>
@endpush
