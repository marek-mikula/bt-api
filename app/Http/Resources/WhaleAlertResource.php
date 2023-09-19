<?php

namespace App\Http\Resources;

use App\Models\WhaleAlert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property WhaleAlert $resource
 */
class WhaleAlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing('currency');

        return [
            'id' => $this->resource->id,
            'currency' => new CurrencyResource($this->resource->currency),
            'hash' => $this->resource->hash,
            'amount' => $this->resource->amount,
            'amount_usd' => $this->resource->amount_usd,
            'sender_address' => $this->resource->sender_address,
            'sender_name' => $this->resource->sender_name,
            'receiver_address' => $this->resource->receiver_address,
            'receiver_name' => $this->resource->receiver_name,
            'notified_at' => $this->resource->notified_at?->toIso8601String(),
        ];
    }
}
