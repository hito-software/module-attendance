<?php

namespace Hito\Modules\Attendance\Database\Seeders;

use Illuminate\Database\Seeder;
use Hito\Modules\Attendance\Services\AttendanceTypeService;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedRealAttendanceTypes();
    }

    private function seedRealAttendanceTypes()
    {
        $items = [
            [
                'name' => 'Remote',
                'description' => '',
                'symbol' => 'R',
                'color' => '#2563eb',
                'status' => 'PUBLISHED'
            ],
            [
                'name' => 'Travel',
                'description' => '',
                'symbol' => 'T',
                'color' => '#7c3aed',
                'status' => 'PUBLISHED'
            ],
            [
                'name' => 'Medical Leave',
                'description' => '',
                'symbol' => 'M',
                'color' => '#dc2626',
                'status' => 'PUBLISHED'
            ],
            [
                'name' => 'Leave',
                'description' => '',
                'symbol' => 'L',
                'color' => '#d97706',
                'status' => 'PUBLISHED'
            ]
        ];

        $attendanceTypeService = app(AttendanceTypeService::class);

        foreach ($items as $type) {
            $attendanceTypeService->create($type['name'], $type['description'], $type['color'], $type['symbol'],
                $type['status']);
        }
    }

}
