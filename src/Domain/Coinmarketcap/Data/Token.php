<?php

namespace Domain\Coinmarketcap\Data;

use Spatie\LaravelData\Data;

class Token extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly string $slug,
        public readonly string $quoteCurrency,
        public readonly float $quotePrice,
    ) {
    }
}
