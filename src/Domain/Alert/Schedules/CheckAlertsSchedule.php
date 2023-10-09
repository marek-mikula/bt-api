<?php

namespace Domain\Alert\Schedules;

use App\Models\Alert;
use App\Schedules\BaseSchedule;
use Domain\Alert\Jobs\ProcessAlertsJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
                $query
                    ->whereNull('time_at')
                    ->orWhere('time_at', '<=', $time);
            })
            ->whereNull('queued_at')
            ->whereNull('notified_at');

        // no alerts to push to queue
        if (! $query->exists()) {
            return;
        }

        $query
            ->pluck('id')
            ->chunk(50)
            ->each(function (Collection $chunk): void {
                // mark alerts as queued
                Alert::query()
                    ->whereIn('id', $chunk->all())
                    ->update([
                        'queued_at' => now(),
                    ]);

                // dispatch job for each chunk
                ProcessAlertsJob::dispatch($chunk->all());
            });
    }
}
