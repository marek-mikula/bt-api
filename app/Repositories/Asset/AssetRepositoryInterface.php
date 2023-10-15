<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

interface AssetRepositoryInterface
{
    /**
     * @return Collection<Asset>
     */
    public function getByUser(User $user): Collection;

    public function findByUserAndCurrency(User $user, Currency $currency): ?Asset;

    public function updateBalanceByOrder(Order $order): void;
}
