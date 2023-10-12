<?php

namespace Domain\Currency\Data;

use App\Data\BaseData;

class BinancePairData extends BaseData
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $baseAsset,
        public readonly string $quoteAsset,
        public readonly ?float $minQuantity,
        public readonly ?float $maxQuantity,
        public readonly ?float $stepSize,
        public readonly ?float $minNotional,
        public readonly ?float $maxNotional,
        public readonly int $baseCurrencyPrecision,
        public readonly int $quoteCurrencyPrecision,
    ) {
    }
}
