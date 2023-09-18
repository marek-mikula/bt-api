<?php

namespace Domain\WhaleAlert\Schedules;

use App\Schedules\BaseSchedule;
use Domain\WhaleAlert\Jobs\CheckWhaleAlertsJob;

class CheckWhaleAlertsSchedule extends BaseSchedule
{
    public function __invoke(): void
    {
        CheckWhaleAlertsJob::dispatch();
    }
}
