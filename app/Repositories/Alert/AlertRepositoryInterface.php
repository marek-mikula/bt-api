<?php

namespace App\Repositories\Alert;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Collection;

interface AlertRepositoryInterface
{
    /**
     * @return Collection<Alert>
     */
    public function index(bool $activeOnly = false): Collection;

    public function create(array $data): Alert;

    public function find(int $id): ?Alert;

    public function findOfUser(User $user, int $id): ?Alert;
}
