<?php

namespace App\Repositories\WhaleAlert;

use App\Models\Currency;
use App\Models\User;
use App\Models\WhaleAlert;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WhaleAlertRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<WhaleAlert>
     */
    public function index(int $page, int $perPage = 100): LengthAwarePaginator;

    /**
     * @return Collection<WhaleAlert>
     */
    public function latest(User $user, int $count = 10, Currency $currency = null): Collection;
}
