<?php

namespace App\Notifications\User;

use App\Mail\User\UserVerifyEmailMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

class UserVerifyEmailNotification extends BaseNotification
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

    public function toMail(User $notifiable): UserVerifyEmailMail
    {
        return new UserVerifyEmailMail($notifiable, $this->mfaToken);
    }
}
