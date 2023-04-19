<?php

namespace App\Repositories\RefreshToken;

use App\Models\RefreshToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(
        private readonly Repository $configRepository
    ) {
    }

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

    public function prolong(RefreshToken $token): RefreshToken
    {
        $ttl = (int) $this->configRepository->get('jwt.refresh_ttl');

        $token->valid_until = Carbon::now()->addMinutes($ttl);
        $token->save();

        return $token;
    }

    public function deleteByDevice(User $user, string $device): void
    {
        RefreshToken::query()
            ->where('user_id', '=', $user->id)
            ->where('device', '=', $device)
            ->delete();
    }
}
