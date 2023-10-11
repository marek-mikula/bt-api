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
            'userId' => $this->resource->user_id,
            'pairId' => $this->resource->pair_id,
            'type' => $this->resource->type->name,
            'status' => $this->resource->status->name,
            'quantity' => $this->resource->quantity,
            'pair' => new CurrencyPairResource($this->whenLoaded('pair')),
        ];
    }
}
