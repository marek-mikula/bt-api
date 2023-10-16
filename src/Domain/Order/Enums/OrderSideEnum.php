<?php

namespace Domain\Order\Enums;

enum OrderSideEnum: string
{
    case BUY = 'BUY';
    case SELL = 'SELL';
}
