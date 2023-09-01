<?php

namespace App\Repositories\Alert;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Collection;

class AlertRepository implements AlertRepositoryInterface
{
    public function index(): Collection
    {
        return Alert::query()
            ->orderBy('notified_at')
            ->orderBy('date_at')
            ->orderBy('time_at')
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
        $alert = Alert::query()
            ->where('user_id', '=', $user->id)
            ->find($id);

        return $alert;
    }
}
