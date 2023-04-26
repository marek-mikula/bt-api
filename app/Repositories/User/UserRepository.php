<?php

namespace App\Repositories\User;

use App\Models\User;
use Carbon\Carbon;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        /** @var User $user */
        $user = User::query()->create($data);

        return $user;
    }

    public function verifyEmail(User $user): User
    {
        $user->email_verified_at = Carbon::now();
        $user->save();

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('email', '=', $email)
            ->first();

        return $user;
    }

    public function changePassword(User $user, string $password): User
    {
        $user->password = $password;
        $user->save();

        return $user;
    }
}
