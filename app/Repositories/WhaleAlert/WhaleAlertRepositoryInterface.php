<?php

namespace App\Repositories\WhaleAlert;

use Illuminate\Pagination\LengthAwarePaginator;

interface WhaleAlertRepositoryInterface
{
    public function index(int $page, int $perPage = 100): LengthAwarePaginator;
}
