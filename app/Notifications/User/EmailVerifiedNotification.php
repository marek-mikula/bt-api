<?php

namespace App\Notifications\User;

use App\Mail\User\EmailVerifiedMail;
use App\Models\User;
use App\Notifications\BaseNotification;

class EmailVerifiedNotification extends BaseNotification
{
    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): EmailVerifiedMail
    {
        return new EmailVerifiedMail($notifiable);
    }
}
