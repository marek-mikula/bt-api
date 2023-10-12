<?php

namespace Domain\Limits\Data;

use App\Data\BaseData;
use Domain\Currency\Enums\MarketCapCategoryEnum;

class LimitQuoteData extends BaseData
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
