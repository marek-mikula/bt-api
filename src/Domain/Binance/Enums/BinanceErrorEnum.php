<?php

namespace Domain\Binance\Enums;

enum BinanceErrorEnum: string
{
    case UNKNOWN = '-1000';
    case UNAUTHORIZED = '-1002';
    case TOO_MANY_REQUESTS = '-1003';
    case TOO_MANY_ORDERS = '-1015';
}
