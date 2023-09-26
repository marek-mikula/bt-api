<?php

namespace Domain\Currency\Data;

use App\Data\BaseData;

class BinanceCurrencyData extends BaseData
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $name,
        public readonly bool $isFiat,
    ) {
    }
}
