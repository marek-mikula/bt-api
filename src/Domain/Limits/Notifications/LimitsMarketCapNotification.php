<?php

namespace Domain\Limits\Notifications;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Models\User;
use App\Notifications\BaseNotification;
use Domain\Limits\Enums\MarketCapCategoryEnum;

class LimitsMarketCapNotification extends BaseNotification
{
    public function __construct(
        private readonly MarketCapCategoryEnum $category,
        private readonly float $value,
        private readonly float $percentage,
        private readonly int $limitFrom,
        private readonly int $limitTo,
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
        return DatabaseNotification::create(NotificationTypeEnum::MARKET_CAP)
            ->title('title')
            ->body('body')
            ->toArray();
    }
}
