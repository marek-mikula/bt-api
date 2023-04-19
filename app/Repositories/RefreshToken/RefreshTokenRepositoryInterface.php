<?php

namespace App\Repositories\RefreshToken;

use App\Models\RefreshToken;
use App\Models\User;

interface RefreshTokenRepositoryInterface
{
    public function create(array $data): RefreshToken;

    public function find(string $token): ?RefreshToken;

    public function findOrFail(string $token): RefreshToken;

    public function deviceExists(User $user, string $device): bool;

    public function prolong(RefreshToken $token): RefreshToken;
}
