<?php

namespace Domain\Alert\Console\Commands;

use Domain\Alert\Services\AlertService;
use Illuminate\Console\Command;

class AlertCheckCommand extends Command
{
    protected $signature = 'alert:check';

    protected $description = 'Checks alerts of users and possibly pushes jobs to queue for notifications.';

    public function handle(AlertService $service): int
    {
        $service->checkAlerts();

        return 0;
    }
}
