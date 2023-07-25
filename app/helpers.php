<?php

use App\Enums\NotificationTypeEnum;
use Illuminate\Support\Str;

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

if (! function_exists('frontend_link')) {
    /**
     * Generates static link to frontend application
     */
    function frontend_link(string $uri, array $params = []): string
    {
        $frontEndUrl = config('app.frontend_url');

        // remove trailing slash if any
        if (Str::startsWith($uri, '/')) {
            $uri = Str::after($uri, '/');
        }

        return vsprintf("%s/{$uri}", array_merge([$frontEndUrl], $params));
    }
}
