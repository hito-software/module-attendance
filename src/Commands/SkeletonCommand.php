<?php

namespace Hito\Modules\Attendance\Commands;

use Illuminate\Console\Command;

class SkeletonCommand extends Command
{
    public $signature = 'zzz:skeleton';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
