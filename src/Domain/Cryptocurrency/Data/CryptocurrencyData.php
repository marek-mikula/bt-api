<?php

namespace Domain\Cryptocurrency\Data;

use App\Data\BaseData;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\DataResource;
use App\Models\Currency;

class CryptocurrencyData extends BaseData
{
    public function __construct(
        public readonly Currency $currency,
        public readonly QuoteData $quote,
    ) {
    }

    public function toResource(): array
    {
        return [
            'currency' => new CurrencyResource($this->currency),
            'quote' => new DataResource($this->quote),
        ];
    }
}
