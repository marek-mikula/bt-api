<?php

namespace App\Repositories\Order;

use App\Models\CurrencyPair;
use App\Models\Order;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function index(User $user, int $page, int $perPage = 50): LengthAwarePaginator;

    public function create(array $data): Order;

    public function getDailyOrderCount(User $user): int;

    public function getWeeklyOrderCount(User $user): int;

    public function getMonthlyOrderCount(User $user): int;

    public function sumWaitingOrderQuote(User $user, CurrencyPair $pair): float;

    public function sumWaitingOrderBase(User $user, CurrencyPair $pair): float;
}
