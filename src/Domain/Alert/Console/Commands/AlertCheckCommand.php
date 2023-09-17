<?php

namespace Domain\Alert\Console\Commands;

use Domain\Alert\Services\AlertCheckerService;
use Illuminate\Console\Command;

class AlertCheckCommand extends Command
{
    protected $signature = 'alert:check';

    protected $description = 'Checks alerts of users and possibly pushes jobs to queue for notifications.';

    public function handle(AlertCheckerService $service): int
    {
        $service->check();

        return 0;
    }
}
