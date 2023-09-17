<?php

namespace App\Repositories\Asset;

use App\Models\User;
use Illuminate\Support\Collection;

class AssetRepository implements AssetRepositoryInterface
{
    public function getByUser(User $user): Collection
    {
        return $user->assets()
            ->orderBy('value', 'desc')
            ->get();
    }
}
