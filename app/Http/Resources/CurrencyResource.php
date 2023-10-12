<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @property Currency $resource
 */
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $meta = $this->resource->meta;

        // transform URLs arrays to single values,
        // so it can be easily accessed on the FE

        if (Arr::has($meta, 'urls')) {
            $meta['urls'] = Arr::map($meta['urls'], static fn (array $value): ?string => Arr::first($value));
        }

        $pivot = [];

        // include pivot data if any

        if ($this->resource->pivot) {
            $pivot = [
                'symbol' => $this->resource->pivot->symbol,
                'minQuantity' => $this->resource->pivot->min_quantity,
                'maxQuantity' => $this->resource->pivot->max_quantity,
                'stepSize' => $this->resource->pivot->step_size,
            ];
        }

        return [
            'id' => $this->resource->id,
            'cmcId' => $this->resource->cmc_id,
            'symbol' => $this->resource->symbol,
            'name' => $this->resource->name,
            'isFiat' => $this->resource->is_fiat,
            'meta' => $meta,
            'pivot' => $this->when(! empty($pivot), $pivot),
            'quotes' => new CurrencyResourceCollection($this->whenLoaded('quoteCurrencies')),
        ];
    }
}
