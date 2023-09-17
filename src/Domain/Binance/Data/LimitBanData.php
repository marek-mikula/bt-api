<?php

namespace Domain\Binance\Data;

use Spatie\LaravelData\Data;

class LimitBanData extends Data
{
    public function __construct(
        public readonly int $timestampMs,
        public readonly int $waitMs,
    ) {
    }
}
