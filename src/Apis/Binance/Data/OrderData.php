<?php

namespace Apis\Binance\Data;

use App\Models\CurrencyPair;
use Domain\Order\Enums\OrderSideEnum;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public readonly string $uuid,
        public readonly float $quantity,
        public readonly CurrencyPair $pair,
        public readonly OrderSideEnum $side,
    ) {
    }
}
