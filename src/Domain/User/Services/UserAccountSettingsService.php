<?php

namespace Domain\User\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Domain\Auth\Notifications\PasswordChangedNotification;
use Domain\User\Http\Requests\Data\SaveAccountKeysSettingsRequestData;
use Domain\User\Http\Requests\Data\SaveAccountPasswordSettingsRequestData;
use Domain\User\Http\Requests\Data\SaveAccountPersonalSettingsRequestData;

class UserAccountSettingsService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function savePersonal(User $user, SaveAccountPersonalSettingsRequestData $data): void
    {
        $this->userRepository->update($user, [
            'firstname' => $data->firstname,
            'lastname' => $data->lastname,
            'birth_date' => $data->birthDate->format('Y-m-d'),
        ]);
    }

    public function savePassword(User $user, SaveAccountPasswordSettingsRequestData $data): void
    {
        $user = $this->userRepository->changePassword($user, $data->newPassword);

        // send notification to user
        $user->notify(new PasswordChangedNotification());
    }

    public function saveKeys(User $user, SaveAccountKeysSettingsRequestData $data): void
    {
        $this->userRepository->update($user, [
            'public_key' => $data->publicKey,
            'secret_key' => $data->secretKey,
        ]);
    }
}
