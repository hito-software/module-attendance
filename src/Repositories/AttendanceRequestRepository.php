<?php

namespace Hito\Modules\Attendance\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Models\AttendanceRequestApproval;

interface AttendanceRequestRepository
{
    public function create(string $userId, string $typeId, Carbon $startDate, ?Carbon $endDate = null,
                           ?string $description = null): AttendanceRequest;

    public function update(string $requestId, array $data): bool;

    public function createApproval(string $requestId, string $userId): AttendanceRequestApproval;

    public function getById(string $id): AttendanceRequest;

    public function getByIds(array $ids): Collection;

    public function getAllByUserId(string $userId): Collection;

    public function getAllPaginatedByUserId(string $userId, ?bool $hasType = null): LengthAwarePaginator;

    public function getAllByApproverId(string $userId, ?bool $hasType = null): Collection;

    public function filter(Carbon $startDate, ?Carbon $endDate = null, ?array $userIds = null,
                           ?array $typeIds = null, ?array $status = null): Collection;
}
