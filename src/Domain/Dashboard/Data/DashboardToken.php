<?php

namespace Domain\Dashboard\Data;

use Spatie\LaravelData\Data;

class DashboardToken extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly string $slug,
        public readonly string $quoteCurrency,
        public readonly float $quotePrice,
        public readonly string $iconUrl,
    ) {
    }
}
