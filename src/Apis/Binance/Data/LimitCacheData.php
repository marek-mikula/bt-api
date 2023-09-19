<?php

namespace Apis\Binance\Data;

use Spatie\LaravelData\Data;

class LimitCacheData extends Data
{
    public function __construct(
        public int $timestampMs,
        public int $weightUsed = 0,
    ) {
    }
}
