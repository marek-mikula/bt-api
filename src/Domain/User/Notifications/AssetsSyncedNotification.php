<?php

namespace Domain\User\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\BaseNotification;

class AssetsSyncedNotification extends BaseNotification
{
    public function via(User $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(User $notifiable): array
    {
        return DatabaseNotification::create(NotificationTypeEnum::ASSETS_SYNCED)
            ->title('title')
            ->toArray();
    }
}
