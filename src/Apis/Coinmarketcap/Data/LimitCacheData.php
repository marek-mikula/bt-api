<?php

namespace Apis\Coinmarketcap\Data;

use App\Data\BaseData;

class LimitCacheData extends BaseData
{
    public function __construct(
        public int $timestampMs,
        public int $tries = 0,
    ) {
    }
}
