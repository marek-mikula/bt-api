<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\Limits\LimitsRepositoryInterface;
use Domain\User\Jobs\SyncAssetsJob;

class UserObserver
{
    public function __construct(
        private readonly LimitsRepositoryInterface $limitsRepository,
    ) {
    }

    public function created(User $user): void
    {
        // create limits model
        $limits = $this->limitsRepository->create([
            'user_id' => $user->id,
        ]);

        $user->setRelation('limits', $limits);

        // dispatch job for assets sync
        SyncAssetsJob::dispatch($user);
    }
}
