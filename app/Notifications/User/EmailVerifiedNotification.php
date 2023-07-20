<?php

namespace App\Notifications\User;

use App\Enums\NotificationDomainEnum;
use App\Enums\NotificationTypeEnum;
use App\Mail\User\EmailVerifiedMail;
use App\Models\User;
use App\Notifications\BaseNotification;

class EmailVerifiedNotification extends BaseNotification
{
    public function via(User $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }

    public function toMail(User $notifiable): EmailVerifiedMail
    {
        return new EmailVerifiedMail($notifiable);
    }

    public function toDatabase(User $notifiable): array
    {
        $type = NotificationTypeEnum::EMAIL_VERIFIED;

        return [
            'type' => $type->value,
            'domain' => NotificationDomainEnum::PROFILE->value,
            'title' => __n($type, 'database', 'title'),
            'body' => __n($type, 'database', 'body'),
        ];
    }
}
