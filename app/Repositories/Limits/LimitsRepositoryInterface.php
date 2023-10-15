<?php

namespace App\Repositories\Limits;

use App\Models\Limits;
use App\Models\User;

interface LimitsRepositoryInterface
{
    public function findOrCreate(User $user): Limits;

    public function create(array $data): Limits;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Limits $limits, array $data): Limits;
}
