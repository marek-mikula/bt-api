<?php

namespace Domain\Alert\Jobs;

use App\Enums\QueueEnum;
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
        $this->onQueue(QueueEnum::ALERTS->value);
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
            ->whereNull('notified_at')
            ->each(static function (Alert $alert) {
                $alert->user->notify(new AlertNotification($alert));
            }, 50);

        Alert::query()
            ->whereIn('id', $this->alertIds)
            ->whereNotNull('queued_at')
            ->whereNull('notified_at')
            ->update([
                'notified_at' => now(),
            ]);
    }
}
