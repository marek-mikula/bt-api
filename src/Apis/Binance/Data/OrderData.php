<?php

namespace Apis\Binance\Data;

use Domain\Cryptocurrency\Enums\OrderTypeEnum;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $symbol,
        public readonly OrderTypeEnum $type,
        public readonly float $quantity,
    ) {
    }
}
