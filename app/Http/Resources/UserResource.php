<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'birthDate' => $this->resource->birth_date->toDateString(),
            'fullName' => $this->resource->full_name,
            'email' => $this->resource->email,
            'quizTaken' => $this->resource->quiz_taken,
            'quizFinishedAt' => $this->resource->quiz_finished_at?->toIso8601String(),
            'assetsSyncedAt' => $this->resource->assets_synced_at?->toIso8601String(),
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
