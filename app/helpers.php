<?php

use App\Enums\NotificationTypeEnum;

if (! function_exists('__n')) {
    /**
     * Translation helper for notifications, which correctly formats the
     * translation key based on notification type, channel and key
     */
    function __n(
        NotificationTypeEnum $type,
        string $channel,
        string $key,
        array $replace = [],
        ?string $locale = null
    ): array|null|string {
        return __("notifications.{$type->value}.{$channel}.{$key}", $replace, $locale);
    }
}
