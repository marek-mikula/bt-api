<?php

namespace Domain\User\Enums;

enum LimitsNotificationPeriodEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
}
