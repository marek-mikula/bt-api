<?php

namespace App\Notifications\User;

use App\Data\Notification\DatabaseNotification;
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
        return DatabaseNotification::create(NotificationTypeEnum::EMAIL_VERIFIED)
            ->title('title')
            ->body('body')
            ->toArray();
    }
}
