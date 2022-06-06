@extends('hito.attendance::_layout')

@section('title', 'Attendance reports')

@section('actions')
    @if(Module::isActive('hito.file-exporter'))
        @if(!empty($report) && $requests->count())
            @can('download', $report)
                <a href="{{ route('attendance.reports.download', $report->id) }}"
                   class="hito-attendance__report__header-btn hito-attendance__report__header-btn--download">
                    <i class="fas fa-file-invoice"></i> Download
                </a>
            @endcan
        @endif
    @endif
@endsection

@section('content')
    @can('create', \Hito\Modules\Attendance\Models\AttendanceReport::class)
        <div class="hito-attendance__report__container">
            <div class="hito-attendance__report__form">
                <form action="{{ route('attendance.reports.store') }}" method="post">
                    @csrf
                    <div class="hito-attendance__report__wrapper">
                        <x-hito::Form.Select title="User" name="users" multiple
                                             :value="!empty($report->users) ? $report->users : []"
                                             :items="$users" placeholder="Select user"/>
                        <x-hito::Form.Select title="Type" name="types" multiple
                                             :value="!empty($report->users) ? $report->types : []"
                                             :items="$types" placeholder="Select type"/>
                        <x-hito::Form.DatePicker title="Start date" name="start_date" :required="true"
                                                 value="{{ !empty($report) ? $report?->start_at?->format('Y-m-d') : '' }}"/>
                        <x-hito::Form.DatePicker title="End date" name="end_date"
                                                 value="{{ !empty($report) ? $report?->end_at?->format('Y-m-d') : '' }}"/>
                    </div>
                    <div class="hito-attendance__report__submit">
                        <button type="submit"
                                class="hito-attendance__report__submit-btn">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            @if(!empty($requests))
                <div class="hito-attendance__report__index">
                    <div class="hito-attendance__report__index-container">
                        @if($requests->count())
                            <div class="hito-attendance__report__index-header">
                                <div class="px-4 col-span-3">Name</div>
                                <div class="px-4 col-span-3">Request date</div>
                                <div class="px-4 col-span-3">Period</div>
                                <div class="px-4 col-span-2">Type</div>
                            </div>
                            <div class="hito-attendance__report__index-content">
                                @foreach($requests as $request)
                                    <div class="hito-attendance__report__index-items">
                                        <div class="hito-attendance__report__index-item--name"
                                             title="{{ $request->user->full_name }}">{{ $request->user->full_name }}</div>
                                        <div class="hito-attendance__report__index-item">{{ $request->created_at->format('Y-m-d') }}</div>
                                        <div class="hito-attendance__report__index-item">{{ $request->start_at->format('Y-m-d ') }} {{ !is_null($request->end_at) ? '-' . $request->end_at->format('Y-m-d') : '' }}</div>
                                        <div class="hito-attendance__report__index-item--last">{{ $request->type?->name }}</div>
                                        <div class="hito-attendance__report__index-show">
                                            @can('view', $request)
                                                <a href="{{ route('attendance.requests.show', $request->id) }}"
                                                   title="Show"
                                                   data-tooltip
                                                   class="hito-attendance__report__header-btn hito-attendance__report__header-btn--open">
                                                    <i class="fas fa-eye"></i></a>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="hito-attendance__report__results">
                                <p>There are no results for the selected filter.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endcan
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                new Choices('#form_type', {
                    removeItemButton: true,
                    editItems: true,
                    noChoicesText: 'No choices to choose from',
                    itemSelectText: 'Select type',
                });
                new Choices('#form_users', {
                    removeItemButton: true,
                    editItems: true,
                    noChoicesText: 'No choices to choose from',
                    itemSelectText: 'Select user',
                });

            }, {
                once: true
            });
        })();

        (function () {
            document.addEventListener('turbo:load', function () {
                setTimeout(() => {

                    flatpickr('#form_start_date', {
                        dateFormat: 'Y-m-d'
                    });

                    flatpickr('#form_end_date', {
                        dateFormat: 'Y-m-d'
                    });
                }, {
                    once: true,
                });
            })
        })();
    </script>
@endpush
