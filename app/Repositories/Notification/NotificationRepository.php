<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getUnreadNotificationsCount($notifiable): int
    {
        return $notifiable->notifications()
            ->whereNull('read_at')
            ->count();
    }

    public function find(string $uuid, $notifiable): ?Notification
    {
        /** @var ?Notification $notification */
        $notification = $notifiable->notifications()
            ->whereKey($uuid)
            ->first();

        return $notification;
    }

    public function markAllAsRead($notifiable): void
    {
        $notifiable->notifications()
            ->whereNull('read_at')
            ->update([
                'read_at' => Carbon::now(),
            ]);
    }

    public function markAsRead(Notification $notification): Notification
    {
        $notification->read_at = Carbon::now();
        $notification->save();

        return $notification;
    }
}
