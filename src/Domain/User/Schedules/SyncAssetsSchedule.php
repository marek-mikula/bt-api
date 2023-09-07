<?php

namespace Domain\User\Schedules;

use App\Models\User;
use App\Schedules\BaseSchedule;
use Carbon\Carbon;
use Domain\User\Jobs\SyncAssetsJob;
use Illuminate\Support\Collection;

class SyncAssetsSchedule extends BaseSchedule
{
    public function __invoke(): void
    {
        // max. we can do 2,400 requests / min to Binance
        // because of the limiter, so we have to respect
        // that, so we don't get banned
        //
        // endpoint's weight is 5, the limits is 12,000/min
        //
        // make it 2300, so we have some safe space for
        // new registrations, because their assets gets
        // processed asap
        //
        // make the max number of batch a 200, so we don't spam
        // the queue that much
        //
        // we will run this code every 15 minutes
        //
        // this is sustainable until 134400 (((7 * 24 * 60) / 15) * 200)
        // users, or if the number of queue workers won't be
        // enough to process this amount of requests

        $batchSize = 200;

        // the batch will be chunked by this number and
        // the jobs will be delayed by 1 minute each, again
        // not to spam the queue

        $chunkSize = 50;

        // we synchronize assets every week
        $timestamp = Carbon::now()->subWeek();

        $query = User::query()
            ->whereNull('assets_synced_at')
            ->orWhere('assets_synced_at', '<=', $timestamp->toDateTimeString());

        // no users to sync
        if (! $query->exists()) {
            return;
        }

        $query
            // order users by the last sync date
            // and take only batch of 200 models
            ->orderBy('assets_synced_at')
            ->limit($batchSize)
            ->chunk($chunkSize, static function (Collection $users, int $page): void {
                foreach ($users as $user) {
                    SyncAssetsJob::dispatch($user)->delay(($page - 1) * 60);
                }
            });
    }
}
