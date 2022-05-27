@extends('hito.attendance::_layout')

@section('title', 'Attendance reports')

@section('actions')
    @if(Module::isActive('hito.file-exporter'))
        @if(!empty($report) && $requests->count())
            @can('download', $report)
                <a href="{{ route('attendance.reports.download', $report->id) }}"
                   class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
                    <i class="fas fa-file-invoice"></i> Download
                </a>
            @endcan
        @endif
    @endif
@endsection

@section('content')
@can('create', \Hito\Modules\Attendance\Models\AttendanceReport::class)
<div class="space-y-4 my-4">
    <div class="bg-white p-4 rounded-lg shadow">
        <form action="{{ route('attendance.reports.store') }}" method="post">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <x-hito::Form.Select title="User" name="users[]" multiple :value="!empty($report->users) ? $report->users : []"
                    :items="$users" placeholder="Select user" />
                <x-hito::Form.Select title="Type" name="types[]" multiple :value="!empty($report->users) ? $report->types : []"
                    :items="$types" placeholder="Select type" />
                <x-hito::Form.DatePicker title="Start date" name="start_date" :required="true"
                    value="{{ !empty($report) ? $report?->start_at?->format('Y-m-d') : '' }}" />
                <x-hito::Form.DatePicker title="End date" name="end_date" value="{{ !empty($report) ? $report?->end_at?->format('Y-m-d') : '' }}" />
            </div>
            <div class="flex justify-end my-2">
                <button type="submit"
                    class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if(!empty($requests))
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="space-y-2">
            @if($requests->count())
                <div class="grid grid-cols-12 py-4 bg-gray-100 rounded font-bold">
                    <div class="px-4 col-span-3">Name</div>
                    <div class="px-4 col-span-3">Request date</div>
                    <div class="px-4 col-span-3">Period</div>
                    <div class="px-4 col-span-2">Type</div>
                </div>
                <div class="space-y-2 flex">
                    @foreach($requests as $request)
                    <div class="grid grid-cols-12 items-center py-1 hover:bg-opacity-25 hover:bg-blue-100">
                        <div class="px-4 col-span-3 truncate"
                             title="{{ $request->user->full_name }}">{{ $request->user->full_name }}</div>
                        <div class="px-4 col-span-3">{{ $request->created_at->format('Y-m-d') }}</div>
                        <div class="px-4 col-span-3">{{ $request->start_at->format('Y-m-d ') }} {{ !is_null($request->end_at) ? '-' . $request->end_at->format('Y-m-d') : '' }}</div>
                        <div class="px-4 col-span-2">{{ $request->type?->name }}</div>
                        <div class="col-span-1 flex items-center flex-wrap gap-2 px-2">
                            @can('view', $request)
                                <a href="{{ route('attendance.requests.show', $request->id) }}" title="Show"
                                   data-tooltip
                                   class="py-1 px-2 rounded block text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                    <i class="fas fa-eye"></i></a>
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
            <div class="text-center">
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
