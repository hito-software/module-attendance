<?php


namespace Hito\Modules\Attendance\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Hito\Modules\Attendance\Models\AttendanceType;

interface AttendanceTypeRepository
{
    public function getAll(): Collection;

    public function create( string $name, string $symbol, string $color, string $description, bool $isUnavailable, ?string $flowId = null, ?string $userId = null): AttendanceType;

    public function getById(string $id): AttendanceType;

    public function getByIds(array $ids): Collection;

    public function update(string $id, array $data): bool;

    public function delete(string $id, ?bool $forceDelete = false): bool;
}
