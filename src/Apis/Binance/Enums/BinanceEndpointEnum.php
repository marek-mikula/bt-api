<?php

namespace Apis\Binance\Enums;

use Apis\Binance\Data\LimitData;

enum BinanceEndpointEnum: string
{
    // wallet endpoints
    case WALLET_SYSTEM_STATUS = 'wallet:system-status';
    case WALLED_ACCOUNT_STATUS = 'wallet:account-status';
    case WALLET_ACCOUNT_SNAPSHOT = 'wallet:account-snapshot';
    case WALLET_ASSETS = 'wallet:assets';
    case WALLET_ALL_COINS = 'wallet:all-coins';

    // market data endpoints
    case MARKET_DATA_EXCHANGE_INFO = 'marketData:exchange-info';
    case MARKET_DATA_SYMBOL_PRICE = 'marketData:symbol-price';

    // spot endpoints
    case SPOT_ACCOUNT = 'spot:account';
    case SPOT_PLACE_ORDER = 'spot:place-order';
    case SPOT_GET_ORDER = 'spot:get-order';

    /**
     * @return LimitData[]
     */
    public function getLimits(): array
    {
        // default limit for IP EPs is 12000 / 1m
        // default limit for UID EPs is 180000 / 1m

        return once(function (): array {
            return match ($this) {
                self::WALLET_SYSTEM_STATUS,
                self::WALLED_ACCOUNT_STATUS,
                self::WALLET_ACCOUNT_SNAPSHOT,
                self::WALLET_ASSETS,
                self::WALLET_ALL_COINS => [
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::IP,
                        value: 12_000
                    ),
                ],
                self::SPOT_ACCOUNT,
                self::SPOT_GET_ORDER,
                self::MARKET_DATA_SYMBOL_PRICE,
                self::MARKET_DATA_EXCHANGE_INFO => [
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::IP,
                        value: 6_000,
                        shared: true
                    ),
                ],
                self::SPOT_PLACE_ORDER => [
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::IP,
                        value: 6_000,
                        shared: true
                    ),
                    new LimitData(
                        period: BinanceLimitPeriodEnum::MINUTE,
                        type: BinanceLimitTypeEnum::UID,
                        value: 6_000,
                        shared: true
                    ),
                ]
            };
        });
    }
}
