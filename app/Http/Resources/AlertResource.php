<?php

namespace App\Http\Resources;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Alert $resource
 */
class AlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'userId' => $this->resource->user_id,
            'content' => $this->resource->content,
            'date' => $this->resource->date_at->format('Y-m-d'),
            'time' => $this->resource->time_at?->format('H:i'),
            'notifiedAt' => $this->resource->notified_at?->toIso8601String(),
        ];
    }
}
