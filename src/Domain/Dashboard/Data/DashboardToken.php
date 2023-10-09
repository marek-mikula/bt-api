<?php

namespace Domain\Dashboard\Data;

use App\Data\BaseData;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;

class DashboardToken extends BaseData
{
    public function __construct(
        public readonly Currency $currency,
        public readonly string $quoteCurrency,
        public readonly float $quotePrice,
    ) {
    }

    public function toResource(): array
    {
        return [
            'currency' => new CurrencyResource($this->currency),
            'quoteCurrency' => $this->quoteCurrency,
            'quotePrice' => $this->quotePrice,
        ];
    }
}
