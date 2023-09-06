<?php

namespace App\Schedules;

class BaseSchedule
{
    public static function make(): static
    {
        return app(static::class);
    }
}
