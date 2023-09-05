<?php

namespace Domain\Binance\Data;

use Spatie\LaravelData\Data;

class LimitCacheData extends Data
{
    public function __construct(
        public int $timestampMs,
        public int $tries = 0,
    ) {
    }
}
