<?php

namespace App\Formatters;

use Carbon\Carbon;

trait DateTimeFormatter
{
    public function formatDate(?Carbon $date, string $default = '-'): string
    {
        if (empty($date)) {
            return $default;
        }

        return $date->format('Y-m-d');
    }

    public function formatDatetime(?Carbon $datetime, string $default = '-'): string
    {
        if (empty($datetime)) {
            return $default;
        }

        return $datetime->format('Y-m-d H:i:s');
    }

    public function formatTime(?Carbon $time, string $default = '-', bool $withSeconds = true): string
    {
        if (empty($time)) {
            return $default;
        }

        return $time->format($withSeconds ? 'H:i:s' : 'H:i');
    }
}
