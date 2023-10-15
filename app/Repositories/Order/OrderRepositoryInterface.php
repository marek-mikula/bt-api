<?php

namespace App\Repositories\Order;

use App\Models\Currency;
use App\Models\CurrencyPair;
use App\Models\Order;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function index(User $user, int $page, int $perPage = 50): LengthAwarePaginator;

    /**
     * @return Collection<Order>
     */
    public function latest(User $user, int $count = 10, Currency $currency = null): Collection;

    public function create(array $data): Order;

    public function getDailyOrderCount(User $user): int;

    public function getWeeklyOrderCount(User $user): int;

    public function getMonthlyOrderCount(User $user): int;

    public function sumWaitingOrderQuote(User $user, CurrencyPair $pair): float;

    public function sumWaitingOrderBase(User $user, CurrencyPair $pair): float;
}
