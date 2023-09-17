<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Currency $resource
 */
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'cmcId' => $this->resource->coinmarketcap_id,
            'symbol' => $this->resource->symbol,
            'name' => $this->resource->name,
            'isFiat' => $this->resource->is_fiat,
            'meta' => $this->resource->meta,
        ];
    }
}
