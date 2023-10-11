<?php

namespace App\Http\Resources;

use App\Models\CurrencyPair;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CurrencyPair $resource
 */
class CurrencyPairResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'symbol' => $this->resource->symbol,
            'baseCurrency' => new CurrencyResource($this->whenLoaded('baseCurrency')),
            'quoteCurrency' => new CurrencyResource($this->whenLoaded('quoteCurrency')),
        ];
    }
}
