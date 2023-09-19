<?php

namespace Apis\Binance\Enums;

enum BinanceLimitPeriodEnum: string
{
    case SECOND = 'S';
    case MINUTE = 'M';
    case HOUR = 'H';
    case DAY = 'D';
}
