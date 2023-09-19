<?php

namespace Apis\Binance\Data;

use Spatie\LaravelData\Data;

class BanCacheData extends Data
{
    public function __construct(
        public readonly int $timestampMs,
        public readonly int $waitMs,
    ) {
    }
}
