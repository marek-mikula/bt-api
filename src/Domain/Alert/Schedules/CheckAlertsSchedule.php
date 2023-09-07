<?php

namespace Domain\Alert\Schedules;

use App\Models\Alert;
use App\Schedules\BaseSchedule;
use Domain\Alert\Jobs\ProcessAlertsJob;
use Illuminate\Database\Eloquent\Builder;

class CheckAlertsSchedule extends BaseSchedule
{
    public function __invoke(): void
    {
        $date = now()->format('Y-m-d');

        $time = now()
            ->setSeconds(0)
            ->format('H:i:s');

        $query = Alert::query()
            ->where('date_at', '=', $date)
            ->where(static function (Builder $query) use ($time): void {
                $query->whereNull('time_at')
                    ->orWhere('time_at', '<=', $time);
            })
            ->whereNull('queued_at')
            ->whereNull('notified_at');

        // no alerts to push to queue
        if (! $query->exists()) {
            return;
        }

        /** @var non-empty-list<int> $ids */
        $ids = $query->pluck('id')->all();

        // mark alerts as queued
        Alert::query()
            ->whereIn('id', $ids)
            ->update([
                'queued_at' => now(),
            ]);

        // dispatch job
        ProcessAlertsJob::dispatch($ids);
    }
}
