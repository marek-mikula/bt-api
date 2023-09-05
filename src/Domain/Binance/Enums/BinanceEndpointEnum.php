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

    public function getWeight(): int
    {
        return once(function (): int {
            return match ($this) {
                self::W_SYSTEM_STATUS,
                self::W_ACCOUNT_STATUS => 1,
                self::W_ACCOUNT_SNAPSHOT => 2400,
                self::W_ASSETS => 5,
            };
        });
    }

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
                ]
            };
        });
    }
}
