<?php

namespace Domain\Alert\Console\Commands;

use Domain\Alert\Schedules\CheckAlertsSchedule;
use Illuminate\Console\Command;

class CheckAlertCommand extends Command
{
    protected $signature = 'alert:check';

    protected $description = 'Dispatches job for alerts check.';

    public function handle(): int
    {
        CheckAlertsSchedule::call();

        $this->info('Job to check alerts dispatched!');

        return 0;
    }
}
