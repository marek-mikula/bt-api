<?php

namespace Domain\Cryptocurrency\Data;

use App\Data\BaseData;
use App\Http\Resources\AssetResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\DataResource;
use App\Models\Asset;
use App\Models\Currency;

class CryptocurrencyListData extends BaseData
{
    public function __construct(
        public readonly Currency $currency,
        public readonly QuoteData $quote,
        public readonly ?Asset $userAsset,
    ) {
    }

    public function toResource(): array
    {
        return [
            'currency' => new CurrencyResource($this->currency),
            'quote' => new DataResource($this->quote),
            'userAsset' => $this->userAsset ? new AssetResource($this->userAsset) : null,
        ];
    }
}
