<?php

namespace App\Notifications;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct()
    {
        // set correct locale for notifications
        $this->locale(app()->getLocale());

        // set default queue for notifications
        $this->onQueue(QueueEnum::NOTIFICATIONS->value);
    }

    public function backoff(): array
    {
        return [10, 30, 60]; // 10s, 30s, 60s
    }
}
