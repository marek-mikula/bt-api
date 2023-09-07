<?php

namespace Domain\Limits\Jobs;

use App\Enums\QueueEnum;
use App\Jobs\BaseJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CheckMarketCapLimitJob extends BaseJob
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
            ->with([
                'limits',
                'assets',
            ])
            ->whereHas('limits', function (Builder $query): void {
                $query->whereIn('id', $this->limitIds);
            })
            ->each(function (User $user): void {
                $this->check($user);
            }, 10);
    }

    private function check(User $user): void
    {

    }
}
