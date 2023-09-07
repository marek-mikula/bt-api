<?php

namespace Domain\Binance\Enums;

use Domain\Binance\Data\LimitData;

enum BinanceEndpointEnum: string
{
    // wallet endpoints
    case W_SYSTEM_STATUS = 'w@system-status';
    case W_ACCOUNT_STATUS = 'w@account-status';
    case W_ACCOUNT_SNAPSHOT = 'w@account-snapshot';
    case W_ASSETS = 'w@assets';

    // market data endpoints
    case MD_TICKER_PRICE = 'md@ticker-price';
    case MD_AVG_PRICE = 'md@avg-price';

    /**
     * @return LimitData[]
     */
    public function getLimits(): array
    {
        // default limit for IP EPs is 12000 / 1m
        // default limit for UID EPs is 180000 / 1m

        return once(function (): array {
            return match ($this) {
                self::W_SYSTEM_STATUS,
                self::W_ACCOUNT_STATUS,
                self::W_ACCOUNT_SNAPSHOT,
                self::W_ASSETS => [
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::IP,
                        value: 12_000
                    ),
                ],
                self::MD_AVG_PRICE,
                self::MD_TICKER_PRICE => [
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::IP,
                        value: 6_000,
                        shared: true
                    ),
                ]
            };
        });
    }
}
