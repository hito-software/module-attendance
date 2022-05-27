<?php

namespace Hito\Modules\Attendance\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Models\AttendanceRequestApproval;
use Hito\Modules\Attendance\Repositories\AttendanceRequestRepository;

class AttendanceRequestServiceImpl implements AttendanceRequestService
{
    public function __construct(private AttendanceRequestRepository $attendanceRequestRepository)
    {
    }

    public function create(string $userId, string $typeId, Carbon $startDate, ?Carbon $endDate = null,
                           ?string $description = null): AttendanceRequest
    {
        return $this->attendanceRequestRepository->create($userId, $typeId, $startDate, $endDate, $description);
    }

    public function update(string $requestId, array $data): bool
    {
        return $this->attendanceRequestRepository->update($requestId, $data);
    }

    public function getById(string $id): AttendanceRequest
    {
        return $this->attendanceRequestRepository->getById($id);
    }

    public function getAllByUserId(string $userId): Collection
    {
        return $this->attendanceRequestRepository->getAllByUserId($userId);
    }

    public function getAllPaginatedByUserId(string $userId,  ?bool $hasType = null): LengthAwarePaginator
    {
        return $this->attendanceRequestRepository->getAllPaginatedByUserId($userId,$hasType);
    }

    public function getAllByApproverId(string $userId, ?bool $hasType = null): Collection
    {
        return $this->attendanceRequestRepository->getAllByApproverId($userId, $hasType);
    }

    public function filter(Carbon $startDate, ?Carbon $endDate = null, ?array $userIds = null, ?array $typeIds = null,
        ?array $status = null): Collection
    {
        return $this->attendanceRequestRepository->filter($startDate, $endDate, $userIds, $typeIds, $status);
    }

    public function createApproval(string $requestId, string $userId): AttendanceRequestApproval
    {
        return $this->attendanceRequestRepository->createApproval($requestId, $userId);
    }
}
