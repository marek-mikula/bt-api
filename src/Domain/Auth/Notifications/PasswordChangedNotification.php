<?php

namespace Domain\Auth\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\BaseNotification;
use Domain\Auth\Mail\PasswordChangedMail;

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
