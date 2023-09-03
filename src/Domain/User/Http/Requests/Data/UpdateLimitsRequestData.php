<?php

namespace Domain\User\Http\Requests\Data;

use Spatie\LaravelData\Data;

class UpdateLimitsRequestData extends Data
{
    public function __construct(
        public readonly bool $tradeEnabled,
        public readonly ?int $tradeDaily,
        public readonly ?int $tradeWeekly,
        public readonly ?int $tradeMonthly,
        public readonly bool $cryptocurrencyEnabled,
        public readonly ?int $cryptocurrencyMin,
        public readonly ?int $cryptocurrencyMax,
        public readonly bool $marketCapEnabled,
        public readonly ?int $marketCapMargin,
        public readonly bool $marketCapMicroEnabled,
        public readonly ?int $marketCapMicro,
        public readonly bool $marketCapSmallEnabled,
        public readonly ?int $marketCapSmall,
        public readonly bool $marketCapMidEnabled,
        public readonly ?int $marketCapMid,
        public readonly bool $marketCapLargeEnabled,
        public readonly ?int $marketCapLarge,
        public readonly bool $marketCapMegaEnabled,
        public readonly ?int $marketCapMega,
    ) {
    }
}
