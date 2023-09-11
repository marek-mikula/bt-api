<?php

namespace Domain\Currency\Data;

use Spatie\LaravelData\Data;

class BinanceCurrencyData extends Data
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $name,
        public readonly bool $isFiat,
    ) {
    }
}
