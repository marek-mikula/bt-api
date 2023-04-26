<?php

namespace App\Notifications\User;

use App\Mail\User\UserPasswordChangedMail;
use App\Models\User;
use App\Notifications\BaseNotification;

class UserPasswordChangedNotification extends BaseNotification
{
    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): UserPasswordChangedMail
    {
        return new UserPasswordChangedMail($notifiable);
    }
}
