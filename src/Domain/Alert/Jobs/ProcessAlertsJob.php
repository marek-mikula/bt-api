<?php

namespace Domain\Alert\Jobs;

use App\Jobs\BaseJob;
use App\Models\Alert;
use Domain\Alert\Notifications\AlertNotification;

class ProcessAlertsJob extends BaseJob
{
    /**
     * @param  non-empty-list<int>  $alertIds
     */
    public function __construct(
        private readonly array $alertIds
    ) {
        //
    }

    public function handle(): void
    {
        if (empty($this->alertIds)) {
            return;
        }

        Alert::query()
            ->with('user')
            ->whereIn('id', $this->alertIds)
            ->whereNotNull('queued_at')
            ->each(function (Alert $alert) {
                $alert->user->notify(new AlertNotification($alert));
            }, 50);

        Alert::query()
            ->whereIn('id', $this->alertIds)
            ->update([
                'notified_at' => now(),
            ]);
    }
}
