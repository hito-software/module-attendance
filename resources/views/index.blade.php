@extends('hito.attendance::_layout')

@section('title', 'Attendance overview')

@section('actions')
    @can('create', \Hito\Modules\Attendance\Models\AttendanceRequest::class)
        <a href="{{ route('attendance.requests.create') }}"
        class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-plus"></i> Make request</a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4">
        <x-hito::Card class="overflow-hidden">
            <div class="relative" data-scrollbar='{"autoHide": "never"}'>
                <div class="hito-attendance__legend">
                    <div class="hito-attendance__legend__item hito-attendance__legend__item--present">
                        <div class="hito-attendance__legend__symbol">P</div>
                        <div class="hito-attendance__legend__name">Present</div>
                    </div>

                    @foreach($attendanceTypes as $type)
                        <div class="hito-attendance__legend__item" style="background-color: {{ $type->color }}">
                            <div class="hito-attendance__legend__symbol">{{ $type->symbol }}</div>
                            <div class="hito-attendance__legend__name truncate" title="{{ $type->name }}">{{ $type->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-hito::Card>

        <x-hito::Card>
            <div class="p-5">
                <div class="hito-attendance__overview">
                    <div class="hito-attendance__overview__side">
                        <div class="hito-attendance__overview__column">
                            <div class="hito-attendance__overview__heading">
                            </div>
                        </div>
                    </div>
                    <div class="relative w-full">
                        <div class="hito-attendance__overview__container" id="hito-attendance__header">
                            <div class="hito-attendance__overview__main">
                                @foreach($days as $item)
                                    <div
                                            class="hito-attendance__overview__column @if($item['isToday']) hito-attendance__overview__column--today @endif">
                                        <div class="hito-attendance__overview__heading">
                                            <div>
                                                <div>{{ $item['text'] }}</div>
                                                <div
                                                        class="hito-attendance__overview__heading-day">{{ $item['day'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hito-attendance__overview__content" data-scrollbar>
                    <div class="hito-attendance__overview">
                        <div class="hito-attendance__overview__side">
                            <div class="hito-attendance__overview__column">
                                @foreach($users as $user)
                                    <div class="hito-attendance__overview__row">
                                        <div class="flex items-center space-x-2">
                                            <x-hito::UserAvatar size="2.5rem" :user="$user"/>
                                            <strong class="hito-attendance__overview__row_name truncate">{{ $user->full_name }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="relative w-full">
                            <div class="hito-attendance__overview__container" id="hito-attendance__body">
                                <div class="hito-attendance__overview__main">
                                    @foreach($days as $item)
                                        <div class="hito-attendance__overview__column">
                                            @foreach($item['events'] as $event)
                                                <div
                                                        class="hito-attendance__overview__row @if($item['isToday']) hito-attendance__overview__row--today @endif">
                                                    @if(!is_null($event) && !is_null($event?->type))
                                                        @include('hito.attendance::_type-pill', ['color' => $event?->type?->color,
            'symbol' => $event?->type?->symbol])
                                                    @else
                                                        @include('hito.attendance::_type-pill', ['color' => 'green',
        'symbol' => 'P'])
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-hito::Card>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sticky-js/1.3.0/sticky.min.js"
            integrity="sha512-3z3zGiu0PabNyuTAAfznBJFpOg4owG9oQQasE5BwiiH5BBwrAjbfgIe0RCdtHJ0BQV1YF2Shgokbz2NziLnkuQ=="
            crossorigin="anonymous"></script>
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                const headerSelector = '#hito-attendance__header .hito-attendance__overview__main';
                const bodySelector = '#hito-attendance__body .hito-attendance__overview__main';

                new window.Flickity(headerSelector, {
                    freeScroll: false,
                    contain: true,
                    prevNextButtons: false,
                    pageDots: false,
                    cellAlign: 'left',
                    sync: bodySelector,
                    on: {
                        ready: function () {
                            setTimeout(() => this.selectCell('.hito-attendance__overview__column--today'));
                        }
                    }
                });

                new window.Flickity(bodySelector, {
                    freeScroll: false,
                    contain: true,
                    prevNextButtons: false,
                    pageDots: false,
                    cellAlign: 'left'
                });
            }, {
                once: true
            });
        })();
    </script>
@endpush
