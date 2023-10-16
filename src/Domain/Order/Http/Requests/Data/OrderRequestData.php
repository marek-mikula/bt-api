<?php

namespace Domain\Order\Http\Requests\Data;

use App\Models\CurrencyPair;
use Domain\Order\Enums\OrderSideEnum;
use Spatie\LaravelData\Data;

class OrderRequestData extends Data
{
    public function __construct(
        public readonly CurrencyPair $pair,
        public readonly float $quantity,
        public readonly OrderSideEnum $side,
        public readonly bool $ignoreLimitsValidation
    ) {
    }
}
