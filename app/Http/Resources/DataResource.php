<?php

namespace App\Http\Resources;

use App\Data\BaseData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property BaseData $resource
 */
class DataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource->toResource();
    }
}
