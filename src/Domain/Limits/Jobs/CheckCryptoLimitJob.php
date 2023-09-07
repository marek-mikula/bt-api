<?php

namespace Domain\Limits\Jobs;

use App\Enums\NotificationTypeEnum;
use App\Enums\QueueEnum;
use App\Jobs\BaseJob;
use App\Models\User;
use Domain\Limits\Notifications\LimitsCryptoNotification;
use Illuminate\Database\Eloquent\Builder;

class CheckCryptoLimitJob extends BaseJob
{
    /**
     * @param  non-empty-list<int>  $limitIds
     */
    public function __construct(
        private readonly array $limitIds
    ) {
        $this->onQueue(QueueEnum::LIMITS->value);
    }

    public function handle(): void
    {
        if (empty($this->limitIds)) {
            return;
        }

        User::query()
            ->with('limits')
            ->whereHas('limits', function (Builder $query): void {
                $query->whereIn('id', $this->limitIds);
            })
            ->withCount('assets')
            ->each(function (User $user): void {
                $this->check($user);
            }, 10);
    }

    private function check(User $user): void
    {
        $limits = $user->loadMissing('limits')->limits;

        $numberOfAssets = (int) $user->assets_count;

        if (! empty($limits->cryptocurrency_max) && $numberOfAssets > $limits->cryptocurrency_max) {
            $user->notify(new LimitsCryptoNotification(
                type: NotificationTypeEnum::CRYPTOCURRENCY_MAX,
                exceededValue: $limits->cryptocurrency_max,
                exceededBy: $numberOfAssets
            ));
        }

        if (! empty($limits->cryptocurrency_min) && $numberOfAssets < $limits->cryptocurrency_min) {
            $user->notify(new LimitsCryptoNotification(
                type: NotificationTypeEnum::CRYPTOCURRENCY_MIN,
                exceededValue: $limits->cryptocurrency_min,
                exceededBy: $numberOfAssets
            ));
        }
    }
}
