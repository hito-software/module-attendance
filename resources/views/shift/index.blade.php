@extends('hito.attendance::_layout')

@section('title', 'My shift')

@section('content')
    <x-hito::Card>
        <div class="p-4">
            <x-hito::Form.DatePicker title="Select week" name="start_date" value="{{ $date }}" />
        </div>
    </x-hito::Card>

    <x-hito::Card>
        <gsd-attendance-shift date="{{ request('date') }}"></gsd-attendance-shift>
    </x-hito::Card>
@endsection

@push('js')
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.querySelector('#form_start_date');

            startDateInput?.addEventListener('change', function () {
                window.location.href = '{{ route('attendance.shift.index') }}?date=' + startDateInput.value;
            });
        });
    </script>
@endpush
