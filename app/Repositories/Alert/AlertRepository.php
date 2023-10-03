<?php

namespace App\Repositories\Alert;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AlertRepository implements AlertRepositoryInterface
{
    public function index(bool $activeOnly = false): Collection
    {
        return Alert::query()
            ->orderBy('notified_at')
            ->orderBy('date_at')
            ->orderBy('time_at')
            ->when($activeOnly, function (Builder $query): void {
                $query->whereNull('notified_at');
            })
            ->get();
    }

    public function create(array $data): Alert
    {
        /** @var Alert $alert */
        $alert = Alert::query()->create($data);

        return $alert;
    }

    public function find(int $id): ?Alert
    {
        /** @var Alert|null $alert */
        $alert = Alert::query()->find($id);

        return $alert;
    }

    public function findOfUser(User $user, int $id): ?Alert
    {
        /** @var Alert|null $alert */
        $alert = Alert::query()->ofUser($user)->find($id);

        return $alert;
    }
}
