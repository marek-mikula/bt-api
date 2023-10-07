<?php

namespace Domain\Currency\Data;

use App\Data\BaseData;

class BinancePairData extends BaseData
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $baseAsset,
        public readonly string $quoteAsset,
    ) {
    }
}
