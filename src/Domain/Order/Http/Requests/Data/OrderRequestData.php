<?php

namespace Domain\Order\Http\Requests\Data;

use App\Models\CurrencyPair;
use Spatie\LaravelData\Data;

class OrderRequestData extends Data
{
    public function __construct(
        public readonly CurrencyPair $pair,
        public readonly float $quantity,
        public readonly bool $ignoreLimitsValidation
    ) {
    }
}
