<?php

namespace Domain\WhaleAlert\Data;

use App\Data\BaseData;

class WhaleAlertGroupData extends BaseData
{
    public function __construct(
        public readonly int $count,
        public readonly float $amount,
        public readonly float $amountUsd,
        public readonly string $currencySymbol,
        public readonly string $currencyName,
    ) {
    }
}
