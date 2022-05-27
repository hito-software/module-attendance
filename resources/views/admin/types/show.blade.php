@extends('hito.attendance::_layout')

@section('title', $type->name)

@section('actions')
    @can('update', $type)
        <a href="{{ route('admin.attendance.types.edit', $type->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $type)
        <a href="{{ route('admin.attendance.types.delete', $type->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="form_symbol" class="block">Symbol</label>
            <div class="border py-2 px-4 rounded w-full">@include('hito.attendance::_type-pill', ['color' => $type->color, 'symbol' => $type->symbol])</div>
        </div>

        <div>
            <label class="block">Description</label>
            <div class="border py-2 px-4 rounded w-full">{{ $type->description }}</div>
        </div>

        <div>
            <label for="form_unavailable" class="block">Is unavailable (unable to work)</label>
            <div class="border py-2 px-4 rounded w-full">{{$type->is_unavailable ? 'Yes' : 'No'}}</div> 
        </div>
    </div>
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                new Choices('#form_unavailable');
            }, {
                once: true
            });
        })();
    </script>
@endpush
