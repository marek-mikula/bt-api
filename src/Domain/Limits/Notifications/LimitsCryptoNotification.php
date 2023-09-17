<?php

namespace Domain\Limits\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\BaseNotification;

class LimitsCryptoNotification extends BaseNotification
{
    public function __construct(
        private readonly NotificationTypeEnum $type,
        private readonly int $exceededValue,
        private readonly int $exceededBy,
    ) {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(User $notifiable): array
    {
        return DatabaseNotification::create($this->type)
            ->title('title')
            ->body('body', [
                'limit' => $this->exceededValue,
                'by' => $this->exceededBy,
            ])
            ->toArray();
    }
}
