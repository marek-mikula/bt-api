<?php

namespace Domain\Currency\Data;

use App\Data\BaseData;

class QuoteData extends BaseData
{
    public function __construct(
        public readonly string $currency,
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
    ) {
    }
}
