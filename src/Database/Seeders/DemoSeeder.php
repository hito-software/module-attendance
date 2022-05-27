<?php

namespace Hito\Modules\Attendance\Database\Seeders;

use Hito\Platform\Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AttendanceTypeSeeder::class);
    }
}
