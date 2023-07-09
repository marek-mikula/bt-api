<?php

namespace App\Notifications\User;

use App\Mail\User\UserNewDeviceMail;
use App\Models\User;
use App\Notifications\BaseNotification;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class UserNewDeviceNotification extends BaseNotification
{
    public function __construct(private readonly AuthenticationLog $authenticationLog)
    {
        //
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): UserNewDeviceMail
    {
        return new UserNewDeviceMail($notifiable, $this->authenticationLog);
    }
}
