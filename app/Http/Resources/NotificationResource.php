<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Notification $resource
 */
class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->id,
            'type' => $this->resource->data_type,
            'domain' => $this->resource->data_domain,
            'title' => $this->resource->data_title,
            'body' => $this->resource->data_body,
            'data' => $this->resource->data_input,
            'readAt' => $this->resource->read_at?->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
