
@extends('hito.attendance::_layout')

@section('title', 'Add attendance type')

@section('content')
    <x-hito::Form action="{{ route('admin.attendance.types.store') }}" method="post">
        @include('hito.attendance::admin.types._form')
        <x-hito::form.submit>Create attendance type</x-hito::form.submit>
    </x-hito::Form>
@endsection
