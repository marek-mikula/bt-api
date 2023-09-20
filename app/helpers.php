<?php

use App\Enums\NotificationTypeEnum;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Http\Client\Response;
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
        string $locale = null
    ): array|string {
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

if (! function_exists('response_from_client')) {
    /**
     * Manually builds response which is returned by HTTP client. Useful
     * for client mocks.
     */
    function response_from_client(int $status = 200, array|string $data = [], array $headers = []): Response
    {
        return new Response(
            new GuzzleResponse(
                status: $status,
                headers: $headers,
                body: is_array($data) ? (string) json_encode($data) : $data
            )
        );
    }
}

if (! function_exists('domain_path')) {
    /**
     * Gets path for specific domain
     */
    function domain_path(string $domain, string $path): string
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        return base_path(empty($path) ? "src/Domain/{$domain}" : "src/Domain/{$domain}/{$path}");
    }
}

if (! function_exists('api_path')) {
    /**
     * Gets path for specific API
     */
    function api_path(string $domain, string $path): string
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        return base_path(empty($path) ? "src/Apis/{$domain}" : "src/Apis/{$domain}/{$path}");
    }
}

if (! function_exists('round_up_to_nearest_multiple')) {
    /**
     * Rounds up $n to the nearest multiple of $multiple
     */
    function round_up_to_nearest_multiple(int $n, int $multiple): int
    {
        return (int) ($multiple * ceil($n / $multiple));
    }
}

if (! function_exists('round_down_to_nearest_multiple')) {
    /**
     * Rounds down $n to the nearest multiple of $multiple
     */
    function round_down_to_nearest_multiple(int $n, int $multiple): int
    {
        return (int) ($multiple * floor($n / $multiple));
    }
}
