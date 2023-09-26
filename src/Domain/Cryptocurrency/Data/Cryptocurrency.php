<?php

namespace Domain\Cryptocurrency\Data;

use App\Data\BaseData;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;

class Cryptocurrency extends BaseData
{
    public function __construct(
        public readonly Currency $currency,
        public readonly string $quoteCurrency,
        public readonly bool $infiniteSupply,
        public readonly float $totalSupply,
        public readonly float $circulatingSupply,
        public readonly int $maxSupply,
        public readonly float $price,
        public readonly float $priceChange1h,
        public readonly float $priceChange24h,
        public readonly float $priceChange7d,
        public readonly float $priceChange30d,
        public readonly float $priceChange60d,
        public readonly float $priceChange90d,
        public readonly float $marketCap,
        public readonly float $volume24h,
        public readonly float $volumeChange24h,
    ) {
    }

    public function toResource(): array
    {
        return [
            'currency' => new CurrencyResource($this->currency),
            'quoteCurrency' => $this->quoteCurrency,
            'infiniteSupply' => $this->infiniteSupply,
            'totalSupply' => $this->totalSupply,
            'circulatingSupply' => $this->circulatingSupply,
            'maxSupply' => $this->maxSupply,
            'price' => $this->price,
            'priceChange1h' => $this->priceChange1h,
            'priceChange24h' => $this->priceChange24h,
            'priceChange7d' => $this->priceChange7d,
            'priceChange30d' => $this->priceChange30d,
            'priceChange60d' => $this->priceChange60d,
            'priceChange90d' => $this->priceChange90d,
            'marketCap' => $this->marketCap,
            'volume24h' => $this->volume24h,
            'volumeChange24h' => $this->volumeChange24h,
        ];
    }
}
