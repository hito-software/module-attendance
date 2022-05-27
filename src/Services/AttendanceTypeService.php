<?php


namespace Hito\Modules\Attendance\Services;


use Illuminate\Support\Collection;
use Hito\Modules\Attendance\Models\AttendanceType;

interface AttendanceTypeService
{
    public function getAll(): Collection;

    public function create(string $name, string $symbol, string $color, string $description, bool $isUnavailable, ?string $flowId = null, ?string $userId = null): AttendanceType;

    public function getById(string $id): AttendanceType;

    public function getByIds(array $ids): Collection;

    public function update(string $id, array $data): bool;

    public function delete(string $id, ?bool $forceDelete = false): bool;

}
