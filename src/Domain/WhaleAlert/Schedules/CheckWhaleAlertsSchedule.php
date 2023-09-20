<?php

namespace Domain\WhaleAlert\Schedules;

use App\Enums\QueueEnum;
use App\Schedules\BaseSchedule;
use Domain\WhaleAlert\Jobs\NotifyWhaleAlertsJob;
use Domain\WhaleAlert\Jobs\SyncWhaleAlertsJob;
use Illuminate\Support\Facades\Bus;

class CheckWhaleAlertsSchedule extends BaseSchedule
{
    public function __invoke(): void
    {
        $batch = collect(config('whale-alert.supported_currencies'))
            ->map(function (string $currency, int $index): SyncWhaleAlertsJob {
                // add delay of 25s for each job, because the limit
                // of WhaleAlert API is 3 requests/1m
                return (new SyncWhaleAlertsJob($currency))->delay($index * 25);
            });

        Bus::batch($batch)
            ->name('Sync whale alerts and notify user')
            ->onQueue(QueueEnum::WHALE_ALERTS->value)
            ->then(function (): void {
                NotifyWhaleAlertsJob::dispatch();
            })
            ->dispatch();
    }
}
