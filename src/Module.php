<?php

namespace Hito\Modules\Attendance;

use Hito\Module\BaseModule;

class Module extends BaseModule
{
    public function getId(): string
    {
        return 'hito.attendance';
    }

    public function getName(): string
    {
        return 'Attendance';
    }

    public function providers(): array
    {
        return [
            AttendanceServiceProvider::class
        ];
    }

    public function publicPath(): ?string
    {
        return __DIR__.'/../public';
    }
}
