<?php

namespace App\Schedules;

class BaseSchedule
{
    /**
     * Calls the schedule via container.
     */
    public static function call(mixed ...$args): void
    {
        app()->call([new static(), '__invoke'], $args);
    }

    /**
     * Proxies the schedule call via callback, so
     * it won't get called immediately.
     */
    public static function proxyCall(mixed ...$args): callable
    {
        return function () use ($args): void {
            static::call(...$args);
        };
    }
}
