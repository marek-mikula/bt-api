<?php

namespace App\Notifications\User;

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
        $type = NotificationTypeEnum::PASSWORD_CHANGED;

        return [
            'type' => $type->value,
            'title' => __n($type, 'database', 'title'),
            'body' => __n($type, 'database', 'body'),
        ];
    }
}
