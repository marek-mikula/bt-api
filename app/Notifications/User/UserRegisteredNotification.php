<?php

namespace App\Notifications\User;

use App\Mail\User\UserRegisteredMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

class UserRegisteredNotification extends BaseNotification
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

    public function toMail(User $notifiable): UserRegisteredMail
    {
        return new UserRegisteredMail($notifiable, $this->mfaToken);
    }
}
