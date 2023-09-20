<?php

namespace Domain\WhaleAlert\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\BaseNotification;
use Domain\WhaleAlert\Data\WhaleAlertGroupData;

class WhaleAlertNotification extends BaseNotification
{
    public function __construct(
        private readonly WhaleAlertGroupData $data,
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
        return DatabaseNotification::create(NotificationTypeEnum::WHALE_ALERT)
            ->title('title', [
                'currencySymbol' => $this->data->currencySymbol,
            ])
            ->body('body', [
                'n' => $this->data->count,
                'currency' => $this->data->currencyName,
                'currencySymbol' => $this->data->currencySymbol,
                'amount' => number_format($this->data->amount, 2),
                'amountUsd' => number_format($this->data->amountUsd, 2),
            ])
            ->input($this->data->toArray())
            ->toArray();
    }
}
