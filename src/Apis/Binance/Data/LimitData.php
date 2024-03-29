<?php

namespace Apis\Binance\Data;

use Apis\Binance\Enums\BinanceLimitPeriodEnum;
use Apis\Binance\Enums\BinanceLimitTypeEnum;
use App\Data\BaseData;

class LimitData extends BaseData
{
    public function __construct(
        public readonly BinanceLimitPeriodEnum $period,
        public readonly BinanceLimitTypeEnum $type,
        public readonly int $value,
        public readonly int $per = 1,
        public readonly bool $shared = false,
    ) {
    }

    public function getPeriodInMs(): int
    {
        return once(function (): int {
            $ms = match ($this->period) {
                BinanceLimitPeriodEnum::SECOND => 1000,
                BinanceLimitPeriodEnum::MINUTE => 60 * 1000,
                BinanceLimitPeriodEnum::HOUR => 60 * 60 * 1000,
                BinanceLimitPeriodEnum::DAY => 24 * 60 * 60 * 1000,
            };

            return $this->per * $ms;
        });
    }
}
