<?php

namespace Domain\Cryptocurrency\Data;

use Spatie\LaravelData\Data;

class Cryptocurrency extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly string $iconUrl,
        public readonly bool $infiniteSupply,
        public readonly float $totalSupply,
        public readonly float $circulatingSupply,
        public readonly int $maxSupply,
        public readonly float $price,
        public readonly float $priceChange1h,
        public readonly float $priceChange24h,
        public readonly float $priceChange7d,
        public readonly float $priceChange30d,
        public readonly float $priceChange60d,
        public readonly float $priceChange90d,
        public readonly float $marketCap,
        public readonly float $volume24h,
        public readonly float $volumeChange24h,
        public readonly string $currency,
    ) {
    }
}
