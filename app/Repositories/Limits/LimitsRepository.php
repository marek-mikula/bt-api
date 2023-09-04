<?php

namespace App\Repositories\Limits;

use App\Models\Limits;

class LimitsRepository implements LimitsRepositoryInterface
{
    public function create(array $data): Limits
    {
        /** @var Limits $limits */
        $limits = Limits::query()->create($data);

        return $limits;
    }

    public function update(Limits $limits, array $data): Limits
    {
        $limits->fill($data)->save();

        // if model has not been modified by
        // data => just update the timestamps
        if (! $limits->isDirty()) {
            $limits->touch();
        }

        return $limits;
    }
}
