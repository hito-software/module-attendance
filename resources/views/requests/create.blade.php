@extends('hito.attendance::_layout')

@section('title', 'Create leave request')

@section('content')
    <x-hito::Form action="{{ route('attendance.requests.store') }}" method="post">
        @include('hito.attendance::requests._form')
        <x-hito::form.submit>Create request</x-hito::form.submit>
    </x-hito::Form>
@endsection
