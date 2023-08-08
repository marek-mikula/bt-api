<?php

namespace Domain\Auth\Services;

use App\Models\User;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Domain\Auth\Enums\MfaTokenTypeEnum;
use Domain\Auth\Notifications\PasswordChangedNotification;
use Domain\Auth\Notifications\ResetPasswordNotification;

class PasswordResetService
{
    public function __construct(
        private readonly MfaTokenRepositoryInterface $mfaTokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function sendEmail(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            return;
        }

        $mfaToken = $this->mfaTokenRepository->create($user, MfaTokenTypeEnum::RESET_PASSWORD);

        $user->notify(new ResetPasswordNotification($mfaToken));
    }

    public function resetPassword(User $user, string $password): void
    {
        $user = $this->userRepository->changePassword($user, $password);

        $user->notify(new PasswordChangedNotification());
    }
}
