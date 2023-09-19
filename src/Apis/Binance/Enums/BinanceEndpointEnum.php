<?php

namespace Apis\Binance\Enums;

use Apis\Binance\Data\LimitData;

enum BinanceEndpointEnum: string
{
    // wallet endpoints
    case W_SYSTEM_STATUS = 'w@system-status';
    case W_ACCOUNT_STATUS = 'w@account-status';
    case W_ACCOUNT_SNAPSHOT = 'w@account-snapshot';
    case W_ASSETS = 'w@assets';
    case W_ALL_COINS = 'w@all-coins';

    // market data endpoints
    case MD_EXCHANGE_INFO = 'md@exchange-info';

    // spot endpoints
    case S_ACCOUNT = 's@account';

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
                self::W_ASSETS,
                self::W_ALL_COINS => [
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::IP,
                        value: 12_000
                    ),
                ],
                self::S_ACCOUNT,
                self::MD_EXCHANGE_INFO => [
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
