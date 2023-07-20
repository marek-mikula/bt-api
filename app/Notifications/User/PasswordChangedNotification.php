<?php

namespace App\Notifications\User;

use App\Mail\User\PasswordChangedMail;
use App\Models\User;
use App\Notifications\BaseNotification;

class PasswordChangedNotification extends BaseNotification
{
    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): PasswordChangedMail
    {
        return new PasswordChangedMail($notifiable);
    }
}
