<?php

namespace Domain\User\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Domain\User\Http\Requests\Data\UpdateNotificationsSettingsRequestData;

class UserNotificationsSettingsService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function update(User $user, UpdateNotificationsSettingsRequestData $data): User
    {
        return $this->userRepository->update($user, [
            'whale_notification_enabled' => $data->whaleEnabled,
        ]);
    }
}
