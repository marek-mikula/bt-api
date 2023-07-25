<?php

namespace App\Notifications\User;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Mail\User\PasswordChangedMail;
use App\Models\User;
use App\Notifications\BaseNotification;

class PasswordChangedNotification extends BaseNotification
{
    public function via(User $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }

    public function toMail(User $notifiable): PasswordChangedMail
    {
        return new PasswordChangedMail($notifiable);
    }

    public function toDatabase(User $notifiable): array
    {
        return DatabaseNotification::create(NotificationTypeEnum::PASSWORD_CHANGED)
            ->title('title')
            ->body('body')
            ->toArray();
    }
}
