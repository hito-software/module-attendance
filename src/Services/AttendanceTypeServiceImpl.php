<?php


namespace Hito\Modules\Attendance\Services;


use Illuminate\Support\Collection;
use Hito\Modules\Attendance\Models\AttendanceType;
use Hito\Modules\Attendance\Repositories\AttendanceTypeRepository;

class AttendanceTypeServiceImpl implements AttendanceTypeService
{
    private AttendanceTypeRepository $attendanceTypeRepository;

    /**
     * AttendanceTypeServiceImpl constructor.
     * @param AttendanceTypeRepository $attendanceTypeRepository
     */
    public function __construct(AttendanceTypeRepository $attendanceTypeRepository)
    {
        $this->attendanceTypeRepository = $attendanceTypeRepository;
    }

    public function getAll(): Collection
    {
        return $this->attendanceTypeRepository->getAll();
    }

    public function create(string $name, string $symbol, string $color, string $description, bool $isUnavailable, ?string $flowId = null, ?string $userId = null): AttendanceType
    {
        return $this->attendanceTypeRepository->create($name, $symbol, $color, $description, $isUnavailable, $flowId, $userId);
    }

    public function getById(string $id): AttendanceType
    {
        return $this->attendanceTypeRepository->getById($id);
    }

    public function getByIds(array $ids): Collection
    {
        return $this->attendanceTypeRepository->getByIds($ids);
    }

    public function update(string $id, array $data): bool
    {
        return $this->attendanceTypeRepository->update($id, $data);
    }

    public function delete(string $id, ?bool $forceDelete = false): bool
    {
        return $this->attendanceTypeRepository->delete($id, $forceDelete);
    }
}
