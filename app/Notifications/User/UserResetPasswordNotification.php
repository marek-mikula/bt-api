<?php

namespace App\Notifications\User;

use App\Mail\User\UserResetPasswordMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

class UserResetPasswordNotification extends BaseNotification
{
    public function __construct(private readonly MfaToken $mfaToken)
    {
        //
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): UserResetPasswordMail
    {
        return new UserResetPasswordMail($notifiable, $this->mfaToken);
    }
}
