<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Collection;

interface AssetRepositoryInterface
{
    /**
     * @return Collection<Asset>
     */
    public function getByUser(User $user): Collection;
}
