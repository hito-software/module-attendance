@extends('hito.attendance::_layout')

@section('title', 'Leave request')

@section('actions')
    <form action="{{ route('attendance.requests.recalculate', $request->id) }}" method="post">
        @csrf
        <button type="submit" data-turbo="false"
                class="hito-attendance__request__header-btn hito-attendance__request__header-btn--recalculate">
            <i class="fas fa-file-invoice"></i> Recalculate
        </button>
    </form>
@endsection

@section('content')
    <div class="hito-attendance__request__container">
        <div class=" hito-attendance__request__wrapper">
            <div class=" hito-attendance__request__wrapper-col">
                <div class="hito-attendance__request__wrapper-space">
                    <div class="hito-attendance__request__label-wrapper">
                        <label class="hito-attendance__request__label-text">Requested by</label>
                        <div><strong>{{ $request->user->full_name }}</strong></div>
                    </div>

                    <div class="hito-attendance__request__label-wrapper">
                        <label class="hito-attendance__request__label-text">Type</label>
                        <div>
                            @include('hito.attendance::_type-pill', ['color' => $request->type->color,
'symbol' => $request->type->symbol, 'name' => $request->type->name])
                        </div>
                    </div>

                    @if(!empty($request->description))
                        <div class="hito-attendance__request__label-wrapper">
                            <label for="form_description"
                                   class="hito-attendance__request__label-text">Description</label>
                            <div>
                                <p>{{ $request->description }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="hito-attendance__request__date">
                        <span class="hito-attendance__request__date-text">
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
            <div class="hito-attendance__request__approval-col">
                <div class="hito-attendance__request__label-wrapper">
                    <div class="hito-attendance__request__approval-border">
                        <div class="hito-attendance__request__approval-space">
                            <div class="hito-attendance__request__approval-label">
                                <label class="hito-attendance__request__approval-text">Approvals</label>
                            </div>
                            @if(!empty($myApproval))
                                <div>
                                    @if(is_null($myApproval->is_approved))
                                        <form action="{{ route('attendance.requests.update-approval', $request->id) }}"
                                              method="post">
                                            @csrf
                                            <div class="hito-attendance__request__approval-actions">
                                                <button type="submit" name="value" value="0"
                                                        class="hito-attendance__request__header-btn hito-attendance__request__header-btn--reject">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                                <button type="submit" name="value" value="1"
                                                        class="hito-attendance__request__header-btn hito-attendance__request__header-btn--approve">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </div>
                                        </form>
                                    @elseif($myApproval->is_approved)
                                        <div class="hito-attendance__request__approved">
                                            <i class="fas fa-check"></i> You approved
                                        </div>
                                    @else
                                        <div class="hito-attendance__request__rejected">
                                            <i class="fas fa-times"></i> You rejected
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="hito-attendance__request__approval-wrapper">
                        <div class="hito-attendance__request__approval-wrapper--space">
                            @foreach($request->approvals as $approval)
                                <div class="hito-attendance__request__approval-user">
                                    <div class="relative">
                                        <x-hito::UserAvatar size="2.5rem" :user="$approval->user"/>
                                        @if(!is_null($approval->is_approved))
                                            <div
                                                    class="hito-attendance__request__approval-items">
                                                @if($approval->is_approved)
                                                    <div
                                                            class="hito-attendance__request__approval-items--green"
                                                            style="width: 20px; height: 20px;">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                @else
                                                    <div
                                                            class="hito-attendance__request__approval-items--red"
                                                            style="width: 20px; height: 20px;">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="hito-attendance__request__users">
                                        <div class="hito-attendance__request__users-items">
                                            <div class="hito-attendance__request__users-text">
                                                {{ $approval->user->full_name }}
                                            </div>
                                            @if(auth()->user()->id === $approval->user->id)
                                                <div>
                                                    <span class="hito-attendance__request__users-item">Me</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="hito-attendance__request__users-email"
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
