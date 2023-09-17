<?php

namespace Domain\Limits\Data;

use Domain\Limits\Enums\MarketCapCategoryEnum;
use Spatie\LaravelData\Data;

class LimitQuoteData extends Data
{
    public function __construct(
        public readonly string $currency,
        public readonly float $price,
        public readonly float $marketCap,
    ) {
    }

    public function getMarketCapCategory(): MarketCapCategoryEnum
    {
        return MarketCapCategoryEnum::createFromValue($this->marketCap);
    }
}
