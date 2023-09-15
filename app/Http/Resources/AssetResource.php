<?php

namespace App\Http\Resources;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Asset $resource
 */
class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'isSupported' => $this->resource->is_supported,
            'currency' => $this->resource->relationLoaded('currency') && $this->resource->currency
                ? new CurrencyResource($this->resource->currency)
                : null,
            'currencySymbol' => $this->resource->currency_symbol,
            'balance' => $this->resource->balance,
        ];
    }
}
