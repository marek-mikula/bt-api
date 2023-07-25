<?php

namespace App\Http\Resources;

use App\Models\MfaToken;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property MfaToken $resource
 */
class MfaTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->resource->type->value,
            'token' => $this->resource->secret_token,
            'validUntil' => $this->resource->valid_until->toIso8601String(),
        ];
    }
}
