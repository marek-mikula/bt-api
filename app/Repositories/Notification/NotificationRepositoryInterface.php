<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Models\Traits\Notifiable;

interface NotificationRepositoryInterface
{
    /**
     * @param  Notifiable  $notifiable
     */
    public function getUnreadNotificationsCount($notifiable): int;

    /**
     * @param  Notifiable  $notifiable
     */
    public function find(string $uuid, $notifiable): ?Notification;

    /**
     * @param  Notifiable  $notifiable
     */
    public function markAllAsRead($notifiable): void;

    public function markAsRead(Notification $notification): Notification;
}
