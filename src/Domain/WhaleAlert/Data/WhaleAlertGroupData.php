<?php

namespace Domain\WhaleAlert\Data;

use Spatie\LaravelData\Data;

class WhaleAlertGroupData extends Data
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
