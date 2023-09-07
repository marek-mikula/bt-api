<?php

namespace Domain\Limits\Enums;

enum LimitsNotificationPeriodEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
}
