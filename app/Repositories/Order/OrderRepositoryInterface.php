<?php

namespace App\Repositories\Order;

use App\Models\CurrencyPair;
use App\Models\Order;
use App\Models\User;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;

    public function getDailyOrderCount(User $user): int;

    public function getWeeklyOrderCount(User $user): int;

    public function getMonthlyOrderCount(User $user): int;

    public function sumWaitingOrderQuotes(User $user, CurrencyPair $pair): float;
}
