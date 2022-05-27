<?php

namespace Hito\Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Hito\Modules\Attendance\Models\AttendanceFlow;

class AttendanceFlowRepositoryImpl implements AttendanceFlowRepository
{
    public function create(?string $name = null, ?string $description = null, ?string $userId = null): AttendanceFlow
    {
        $data = compact('name',  'description');
        $data['user_id'] = $userId;

        return AttendanceFlow::create($data);
    }

    public function getById(string $id): AttendanceFlow
    {
        return AttendanceFlow::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return AttendanceFlow::all();
    }

    public function getAllPaginated(): LengthAwarePaginator
    {
        return AttendanceFlow::whereStatus('PUBLISHED')->paginate();
    }

    public function update(string $id, array $data): bool
    {
        return AttendanceFlow::whereId($id)->update($data);
    }
}
