@extends('hito.attendance::_layout')

@section('title', 'Attendance types')

@section('actions')
    @can('create',\Hito\Modules\Attendance\Models\AttendanceType::class)
        <a href="{{ route('admin.attendance.types.create') }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-plus"></i> Create
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <table class="w-full">
            <thead>
            <th>Name</th>
            <th>Description</th>
            <th>Symbol</th>
            <th></th>
            </thead>

            @foreach($types as $type)
                <tr class="hover:bg-gray-100 ">
                    <td class="p-2 text-center">{{ $type->name }}</td>
                    <td class="p-2 text-center">{{ $type->description }}</td>
                    <td class="p-2 flex justify-center">
                        @include('hito.attendance::_type-pill', ['color' => $type->color,
'symbol' => $type->symbol])
                    </td>
                    <td class="p-2 text-right">
                        @can('view', $type)
                            <a href="{{ route('admin.attendance.types.show', $type->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $type)
                            <a href="{{ route('admin.attendance.types.edit', $type->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $type)
                            <a href="{{ route('admin.attendance.types.delete', $type->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
