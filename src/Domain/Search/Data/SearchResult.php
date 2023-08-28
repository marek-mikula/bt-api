<?php

namespace Domain\Search\Data;

use Spatie\LaravelData\Data;

class SearchResult extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly string $description,
        public readonly string $logo,
        public readonly array $urls,
        public readonly float $price,
        public readonly string $priceCurrency,
    ) {
    }
}
