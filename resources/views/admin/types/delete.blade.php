@extends('hito.attendance::_layout')

@section('title', 'Delete attendance type')

@section('content')
    <form action="{{ route('admin.attendance.types.destroy', $type->id) }}" method="post">
        @csrf
        @method('delete')
        @include('shared.delete-card', [
    'message' => 'Are you sure you want to delete this attendance type?',
    'noRoute' => route('admin.attendance.types.show', $type->id)
])
    </form>
@endsection
