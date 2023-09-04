<?php

namespace Domain\Alert\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\Alert;
use App\Models\User;
use App\Notifications\BaseNotification;
use Domain\Alert\Mail\AlertMail;
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
        $channels = [];

        if ($this->alert->as_mail) {
            $channels[] = 'mail';
        }

        if ($this->alert->as_notification) {
            $channels[] = 'database';
        }

        return $channels;
    }

    public function toMail(User $notifiable): AlertMail
    {
        return new AlertMail($notifiable, $this->alert);
    }

    public function toDatabase(User $notifiable): array
    {
        return DatabaseNotification::create(NotificationTypeEnum::ALERT)
            ->title('title', [
                'title' => $this->alert->title,
            ])
            ->when(! empty($this->alert->content), function (DatabaseNotification $notification): void {
                $notification->body('body', [
                    'content' => $this->alert->content,
                ]);
            })
            ->input([
                'alertId' => $this->alert->id,
            ])
            ->toArray();
    }
}
