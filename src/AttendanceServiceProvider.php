<?php

namespace Hito\Modules\Attendance;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hito\Modules\Attendance\Commands\SkeletonCommand;

class AttendanceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/theme-views', 'hito');
        $this->loadViewsFrom(__DIR__.'/../resources/views', (new Module())->getId());

        $package
            ->name('attendance')
            ->hasRoute('web')
            ->hasCommand(SkeletonCommand::class);
    }
}
