<?php

namespace App\Repositories\Limits;

use App\Models\Limits;

interface LimitsRepositoryInterface
{
    public function create(array $data): Limits;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Limits $limits, array $data): Limits;
}
