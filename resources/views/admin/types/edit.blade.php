
@extends('hito.attendance::_layout')

@section('title', 'Edit attendance types')

@section('content')
    <x-hito::Form action="{{ route('admin.attendance.types.update', $type->id) }}" method="patch">
        @include('hito.attendance::admin.types._form')
        <x-hito::form.submit>Update attendance type</x-hito::form.submit>
    </x-hito::Form>
@endsection
