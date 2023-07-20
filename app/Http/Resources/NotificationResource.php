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
            'type' => $this->resource->_type,
            'domain' => $this->resource->_domain,
            'title' => $this->resource->_title,
            'body' => $this->resource->_body,
            'data' => $this->resource->_data,
            'readAt' => $this->resource->read_at?->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
