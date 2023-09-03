<?php

namespace Domain\Alert\Services;

use App\Models\Alert;
use Domain\Alert\Jobs\ProcessAlertsJob;
use Illuminate\Database\Eloquent\Builder;

class AlertService
{
    public function checkAlerts(): void
    {
        $date = now()->format('Y-m-d');

        $time = now()
            ->setSeconds(0)
            ->format('H:i:s');

        $query = Alert::query()
            ->where('date_at', '=', $date)
            ->where(function (Builder $query) use ($time): void {
                $query->whereNull('time_at')
                    ->orWhere('time_at', '<=', $time);
            })
            ->whereNull('queued_at')
            ->whereNull('notified_at');

        // no alerts to push to queue
        if (! $query->exists()) {
            return;
        }

        $ids = $query->pluck('id')->toArray();

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
