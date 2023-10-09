<?php

namespace App\Repositories\WhaleAlert;

use App\Models\Currency;
use App\Models\Query\WhaleAlertQuery;
use App\Models\User;
use App\Models\WhaleAlert;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WhaleAlertRepository implements WhaleAlertRepositoryInterface
{
    public function index(int $page, int $perPage = 50): LengthAwarePaginator
    {
        return WhaleAlert::query()
            ->with('currency')
            ->latest('transaction_at')
            ->paginate(
                perPage: $perPage,
                page: $page
            );
    }

    public function latest(User $user, int $count = 10, Currency $currency = null): Collection
    {
        return WhaleAlert::query()
            ->with('currency')
            ->latest('transaction_at')
            ->limit($count)
            ->when($currency !== null, static function (WhaleAlertQuery $query) use ($currency): void {
                $query->ofCurrency($currency);
            })
            ->get();
    }
}
