<?php

namespace Domain\Cryptocurrency\Data;

use App\Data\BaseData;
use App\Http\Resources\AssetResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\DataResource;
use App\Http\Resources\DataResourceCollection;
use App\Http\Resources\OrderResourceCollection;
use App\Http\Resources\WhaleAlertResourceCollection;
use App\Models\Asset;
use App\Models\Currency;
use Illuminate\Support\Collection;

class CryptocurrencyShowData extends BaseData
{
    public function __construct(
        public readonly Currency $currency,
        public readonly QuoteData $quote,
        public readonly Collection $news,
        public readonly Collection $orders,
        public readonly ?Asset $userAsset,
        public readonly ?Collection $whaleAlerts,
    ) {
    }

    public function toResource(): array
    {
        return [
            'currency' => new CurrencyResource($this->currency),
            'quote' => new DataResource($this->quote),
            'news' => new DataResourceCollection($this->news),
            'orders' => new OrderResourceCollection($this->orders),
            'userAsset' => $this->userAsset !== null
                ? new AssetResource($this->userAsset)
                : null,
            'whaleAlerts' => $this->whaleAlerts !== null
                ? new WhaleAlertResourceCollection($this->whaleAlerts)
                : null,
        ];
    }
}
