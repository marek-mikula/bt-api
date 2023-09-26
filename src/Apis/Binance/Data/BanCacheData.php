<?php

namespace Apis\Binance\Data;

use App\Data\BaseData;

class BanCacheData extends BaseData
{
    public function __construct(
        public readonly int $timestampMs,
        public readonly int $waitMs,
    ) {
    }
}
