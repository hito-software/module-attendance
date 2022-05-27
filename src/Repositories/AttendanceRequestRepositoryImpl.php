<?php

namespace Hito\Modules\Attendance\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Models\AttendanceRequestApproval;

class AttendanceRequestRepositoryImpl implements AttendanceRequestRepository
{
    public function create(string $userId, string $typeId, Carbon $startDate, ?Carbon $endDate = null,
                           ?string $description = null): AttendanceRequest
    {
        return AttendanceRequest::create([
            'user_id' => $userId,
            'type_id' => $typeId,
            'start_at' => $startDate,
            'end_at' => $endDate,
            'description' => $description
        ]);
    }

    public function update(string $requestId, array $data): bool
    {
        $this->getById($requestId)->update($data);
        return true;
    }

    public function createApproval(string $requestId, string $userId): AttendanceRequestApproval
    {
        return AttendanceRequestApproval::create([
            'attendance_request_id' => $requestId,
            'user_id' => $userId
        ]);
    }

    public function getById(string $id): AttendanceRequest
    {
        return AttendanceRequest::findOrFail($id);
    }

    public function getByIds(array $ids): Collection
    {
        return AttendanceRequest::whereIn('id', $ids)->get();
    }

    public function getAllByUserId(string $userId): Collection
    {
        return AttendanceRequest::whereUserId($userId)->get();
    }

    public function getAllPaginatedByUserId(string $userId, ?bool $hasType = null): LengthAwarePaginator
    {
        $query = AttendanceRequest::whereUserId($userId);

        
        return $query->whereHas('type', function($q) use($hasType) {
            $q->withTrashed();

            if ($hasType === null) {
                return;
            }

            if ($hasType) {
                $q->whereNull('deleted_at');
            } else {
                $q->whereNotNull('deleted_at');
            }
        })->paginate();
   }
    
    public function getAllByApproverId(string $userId, ?bool $hasType = null): Collection
    {
        $approvals = AttendanceRequestApproval::whereUserId($userId)
        ->whereHas('attendanceRequest.type', function($q) use($hasType) {
            if($hasType === null){
                return;
            }

            if($hasType){
                $q->whereNull('deleted_at');
            } else{
                $q->whereNotNull('deleted_at');
            }
        })->pluck('attendance_request_id');

        return $this->getByIds($approvals->toArray());
    }

    public function filter(Carbon $startDate, ?Carbon $endDate = null, ?array $userIds = null,
                           ?array $typeIds = null, ?array $status = null): Collection
    {
        if ($endDate === null) {
            $query = AttendanceRequest::whereStartAt($startDate);
        } else {
            $query = AttendanceRequest::whereBetween('start_at', [$startDate, $endDate]);
        }

        if (!empty($userIds)) {
            $query->whereIn('user_id', $userIds);
        }

        if (!empty($typeIds)) {
            $query->whereIn('type_id', $typeIds);
        }

        if (!empty($status)) {
            $query->whereIn('status', $status);
        }

        $query->whereHas('type', function($q) {
            $q->withTrashed();
        });

        return $query->get();
    }
}
