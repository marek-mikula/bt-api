<?php

namespace Domain\Alert\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\Alert;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Queue\Attributes\WithoutRelations;

class AlertNotification extends BaseNotification
{
    public function __construct(
        #[WithoutRelations]
        private readonly Alert $alert,
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
        return DatabaseNotification::create(NotificationTypeEnum::ALERT)
            ->title('title')
            ->body('body', [
                'content' => $this->alert->content,
            ])
            ->input([
                'alertId' => $this->alert->id,
            ])
            ->toArray();
    }
}
