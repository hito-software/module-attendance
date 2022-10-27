<?php

namespace Hito\Modules\Attendance;

use Hito\Core\Database\Enums\SeederType;
use Hito\Core\Module\DTO\MenuDTO;
use Hito\Core\Module\DTO\MenuItemDTO;
use Hito\Core\Module\Facades\Menu;
use Hito\Module\ServiceProvider;
use Hito\Modules\Attendance\Database\Seeders\DatabaseSeeder;
use Hito\Modules\Attendance\Database\Seeders\DemoSeeder;
use Hito\Modules\Attendance\Models\AttendanceFlow;
use Hito\Modules\Attendance\Models\AttendanceReport;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Models\AttendanceType;
use Hito\Modules\Attendance\Providers\AppServiceProvider;
use Hito\Modules\Attendance\Providers\AuthServiceProvider;
use Hito\Modules\Attendance\Providers\EventServiceProvider;
use Hito\Modules\Attendance\Providers\RouteServiceProvider;

class AttendanceServiceProvider extends ServiceProvider
{
    protected function getNamespace(): string
    {
        return (new Module())->getId();
    }

    public function register(): void
    {
        $this->app->register(AppServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    protected function configure(): void
    {
        $this->registerMigrations(__DIR__ . '/../database/migrations');
        $this->registerViews(__DIR__ . '/../resources/views');
        $this->registerTranslations(__DIR__ . '/../resources/views');
        $this->registerConfig(__DIR__ . '/../config/config.php');

        $this->registerSeeder(DatabaseSeeder::class, SeederType::MAIN);
        $this->registerSeeder(DemoSeeder::class, SeederType::DEMO);

        $this->createPublicMenus();
        $this->createAdminMenus();
    }

    private function createPublicMenus(): void
    {
        $menu = new MenuDTO('attendance', 'Attendance', 'fas fa-calendar', order: 2);
        $menu->addItem(new MenuItemDTO('Overview', 'attendance.index', 'fas fa-calendar', 'attendance.view'));
        $menu->addItem(new MenuItemDTO('Requests', 'attendance.requests.index', 'fas fa-calendar',['viewAny', AttendanceRequest::class]));
        $menu->addItem(new MenuItemDTO('Reports', 'attendance.reports.index', 'fas fa-calendar', ['viewAny', AttendanceReport::class]));
        $menu->addItem(new MenuItemDTO('My shift', 'attendance.shift.index', 'fas fa-calendar', 'shift.index'));

        Menu::add($menu);
    }

    private function createAdminMenus(): void
    {
        $menu = new MenuDTO('attendance', 'Attendance', 'fas fa-calendar', order: 2, group: 'admin');
        $menu->addItem(new MenuItemDTO('Attendance types', 'admin.attendance.types.index', 'fas fa-calendar',['viewAny', AttendanceType::class]));
        $menu->addItem(new MenuItemDTO('Approval flows', 'admin.attendance.flows.index', 'fas fa-calendar',['viewAny', AttendanceFlow::class]));

        Menu::add($menu);
    }
}
