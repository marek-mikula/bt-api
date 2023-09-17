<?php

namespace App\Enums;

enum QueueEnum: string
{
    case NOTIFICATIONS = 'notifications';
    case ASSETS = 'assets';
    case ALERTS = 'alerts';
    case LIMITS = 'limits';
}
