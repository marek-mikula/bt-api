<?php

namespace Domain\Cryptocurrency\Data;

use Spatie\LaravelData\Data;

class Cryptocurrency extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly float $price,
        public readonly string $priceCurrency,
        public readonly string $iconUrl,
    ) {
    }
}
