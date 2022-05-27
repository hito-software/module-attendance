<?php


namespace Hito\Modules\Attendance\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Hito\Modules\Attendance\Models\AttendanceType;

class AttendanceTypeRepositoryImpl implements AttendanceTypeRepository
{

    public function getAll(): Collection
    {
        return AttendanceType::all();
    }

    public function create(string $name, string $symbol, string $color, string $description, bool $isUnavailable, ?string $flowId = null, ?string $userId = null): AttendanceType
    {
        $data = compact('name', 'symbol', 'color', 'description');
        $data['is_unavailable'] = $isUnavailable;
        $data['attendance_flow_id'] = $flowId;
        $data['user_id'] = $userId;

        return  AttendanceType::create($data);
    }

    public function getById(string $id): AttendanceType
    {
        return AttendanceType::findOrFail($id);
    }

    public function getByIds(array $ids): Collection
    {
        return AttendanceType::whereIn('uuid', $ids)->get();
    }

    public function update(string $id, array $data): bool
    {
        return AttendanceType::whereId($id)->update($data);
    }

    public function delete(string $id, ?bool $forceDelete = false): bool
    {
        $model = $this->getById($id);

        return $forceDelete ? $model->forceDelete() : $model->delete();
    }
}
