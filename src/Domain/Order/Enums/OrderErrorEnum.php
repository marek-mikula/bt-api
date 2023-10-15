<?php

namespace Domain\Order\Enums;

enum OrderErrorEnum
{
    // binance checks
    case MIN_QUANTITY_EXCEEDED;
    case MAX_QUANTITY_EXCEEDED;
    case STEP_SIZE_INVALID;
    case MIN_NOTIONAL_EXCEEDED;
    case MAX_NOTIONAL_EXCEEDED;
    case NO_FUNDS;

    // number of trades limits
    case DAILY_TRADES_EXCEEDED;
    case WEEKLY_TRADES_EXCEEDED;
    case MONTHLY_TRADES_EXCEEDED;

    // number of assets in wallet limits
    case MAX_ASSETS_EXCEEDED;
    case MIN_ASSETS_EXCEEDED;

    // market cap limits
    case MARKET_CAP_EXCEEDED;
}
