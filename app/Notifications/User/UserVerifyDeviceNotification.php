<?php

namespace App\Notifications\User;

use App\Mail\User\UserVerifyDeviceMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

class UserVerifyDeviceNotification extends BaseNotification
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

    public function toMail(User $notifiable): UserVerifyDeviceMail
    {
        return new UserVerifyDeviceMail($notifiable, $this->mfaToken);
    }
}
