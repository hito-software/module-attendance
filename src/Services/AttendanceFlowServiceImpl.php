<?php

namespace Hito\Modules\Attendance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Modules\Attendance\Repositories\AttendanceFlowRepository;

class AttendanceFlowServiceImpl implements AttendanceFlowService
{
    public function __construct(private AttendanceFlowRepository $attendanceFlowRepository)
    {
    }

    public function create(?string $name = null, ?string $description = null, ?string $userId = null): AttendanceFlow
    {
        return $this->attendanceFlowRepository->create($name, $description, $userId);
    }

    public function getById(string $id): AttendanceFlow
    {
        return $this->attendanceFlowRepository->getById($id);
    }

    public function getAll(): Collection
    {
        return $this->attendanceFlowRepository->getAll();
    }

    public function getAllPaginated(): LengthAwarePaginator
    {
        return $this->attendanceFlowRepository->getAllPaginated();
    }

    public function update(string $id, array $data): bool
    {
        return $this->attendanceFlowRepository->update($id, $data);
    }

    public function groupBlocksAndConditions(AttendanceFlow $flow): array
    {
        $blocks = $flow->blocks()->where('parent_id', null)->with('children')->get();

        $data = collect([
            'condition' => $flow->condition,
            'items' => $blocks->map(function ($parent) {
                return [
                    'condition' => $parent->condition,
                    'items' => $parent->children->map(function ($child) {
                        return [
                            'type' => $child->type,
                            'value' => $child->value,
                            'min' => $child->min,
                            'order' => $child->order
                        ];
                    })
                ];
            })
        ]);

        return json_decode($data->toJson(), true, 512, JSON_THROW_ON_ERROR);
    }
}
