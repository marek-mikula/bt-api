<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\Limits\LimitsRepositoryInterface;

class UserObserver
{
    public function __construct(
        private readonly LimitsRepositoryInterface $limitsRepository,
    ) {
    }

    public function created(User $user): void
    {
        $limits = $this->limitsRepository->create([
            'user_id' => $user->id,
        ]);

        $user->setRelation('limits', $limits);
    }
}
