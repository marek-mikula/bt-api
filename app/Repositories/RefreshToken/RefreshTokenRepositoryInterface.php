<?php

namespace App\Repositories\RefreshToken;

use App\Models\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function create(array $data): RefreshToken;

    public function find(string $token): ?RefreshToken;

    public function findOrFail(string $token): RefreshToken;
}
