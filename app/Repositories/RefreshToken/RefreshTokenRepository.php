<?php

namespace App\Repositories\RefreshToken;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function create(array $data): RefreshToken
    {
        /** @var RefreshToken $refreshToken */
        $refreshToken = RefreshToken::query()->create($data);

        return $refreshToken;
    }

    public function find(string $token): ?RefreshToken
    {
        /** @var RefreshToken|null $refreshToken */
        $refreshToken = RefreshToken::query()
            ->where('token', '=', $token)
            ->first();

        return $refreshToken;
    }

    public function findOrFail(string $token): RefreshToken
    {
        $refreshToken = $this->find($token);

        if (! $refreshToken) {
            throw (new ModelNotFoundException())->setModel(RefreshToken::class, $token);
        }

        return $refreshToken;
    }

    public function deviceExists(User $user, string $device): bool
    {
        return RefreshToken::query()
            ->where('user_id', '=', $user->id)
            ->where('device', '=', $device)
            ->exists();
    }
}
