<?php

namespace Domain\WhaleAlert\Console\Commands;

use Domain\WhaleAlert\Schedules\CheckWhaleAlertsSchedule;
use Illuminate\Console\Command;

class CheckWhaleAlertCommand extends Command
{
    protected $signature = 'whale-alert:check';

    protected $description = 'Dispatches job for WhaleAlert API check.';

    public function handle(): int
    {
        CheckWhaleAlertsSchedule::call();

        return 0;
    }
}
