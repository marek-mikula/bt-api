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
            'amountUsd' => $this->resource->amount_usd,
            'senderAddress' => $this->resource->sender_address,
            'senderName' => $this->resource->sender_name,
            'receiverAddress' => $this->resource->receiver_address,
            'receiverName' => $this->resource->receiver_name,
            'notifiedAt' => $this->resource->notified_at?->toIso8601String(),
            'transactionAt' => $this->resource->transaction_at->toIso8601String(),
        ];
    }
}
