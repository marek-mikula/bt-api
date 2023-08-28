<?php

namespace Domain\User\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Domain\Auth\Notifications\PasswordChangedNotification;
use Domain\User\Http\Requests\Data\SaveAccountKeysRequestData;
use Domain\User\Http\Requests\Data\SaveAccountPasswordRequestData;
use Domain\User\Http\Requests\Data\SaveAccountPersonalRequestData;

class UserAccountSettingsService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function savePersonal(User $user, SaveAccountPersonalRequestData $data): void
    {
        $this->userRepository->update($user, [
            'firstname' => $data->firstname,
            'lastname' => $data->lastname,
            'birth_date' => $data->birthDate->format('Y-m-d'),
        ]);
    }

    public function savePassword(User $user, SaveAccountPasswordRequestData $data): void
    {
        $user = $this->userRepository->changePassword($user, $data->newPassword);

        // send notification to user
        $user->notify(new PasswordChangedNotification());
    }

    public function saveKeys(User $user, SaveAccountKeysRequestData $data): void
    {
        $this->userRepository->update($user, [
            'public_key' => $data->publicKey,
            'secret_key' => $data->secretKey,
        ]);
    }
}
