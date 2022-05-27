<?php

namespace Hito\Modules\Attendance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Hito\Modules\Attendance\Models\AttendanceFlow;

interface AttendanceFlowService
{
    public function create(?string $name = null, ?string $description = null, ?string $userId = null): AttendanceFlow;

    public function getById(string $id): AttendanceFlow;

    public function getAll(): Collection;

    public function getAllPaginated(): LengthAwarePaginator;

    public function update(string $id, array $data): bool;

    public function groupBlocksAndConditions(AttendanceFlow $flow): array;
}
