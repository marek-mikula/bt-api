<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Order $resource
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'binanceUuid' => $this->resource->binance_uuid,
            'binanceId' => $this->resource->binance_id,
            'userId' => $this->resource->user_id,
            'pairId' => $this->resource->pair_id,
            'side' => $this->resource->side->name,
            'status' => $this->resource->status->name,
            'baseQuantity' => $this->resource->base_quantity,
            'quoteQuantity' => $this->resource->quote_quantity,
            'price' => $this->resource->price,
            'pair' => new CurrencyPairResource($this->whenLoaded('pair')),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
