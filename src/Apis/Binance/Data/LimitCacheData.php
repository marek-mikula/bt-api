<?php

namespace Apis\Binance\Data;

use App\Data\BaseData;

class LimitCacheData extends BaseData
{
    public function __construct(
        public int $timestampMs,
        public int $weightUsed = 0,
    ) {
    }
}
