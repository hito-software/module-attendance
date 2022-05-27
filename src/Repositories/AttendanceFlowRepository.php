<?php

namespace Hito\Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Hito\Modules\Attendance\Models\AttendanceFlow;

interface AttendanceFlowRepository
{
    public function create(?string $name = null, ?string $description = null, ?string $userId = null): AttendanceFlow;

    public function getAll(): Collection;

    public function getAllPaginated(): LengthAwarePaginator;

    public function getById(string $id): AttendanceFlow;

    public function update(string $id, array $data): bool;
}
