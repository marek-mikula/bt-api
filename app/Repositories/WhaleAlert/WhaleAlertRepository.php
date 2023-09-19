<?php

namespace App\Repositories\WhaleAlert;

use App\Models\WhaleAlert;
use Illuminate\Pagination\LengthAwarePaginator;

class WhaleAlertRepository implements WhaleAlertRepositoryInterface
{
    public function index(int $page, int $perPage = 50): LengthAwarePaginator
    {
        return WhaleAlert::query()
            ->with('currency')
            ->latest('id')
            ->paginate(
                perPage: $perPage,
                page: $page
            );
    }
}
