<?php

namespace Domain\Binance\Enums;

enum BinanceLimitTypeEnum: string
{
    case IP = 'IP';
    case UID = 'UID';
}
