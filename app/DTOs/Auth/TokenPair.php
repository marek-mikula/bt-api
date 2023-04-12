<?php

namespace App\DTOs\Auth;

use Spatie\LaravelData\Data;

class TokenPair extends Data
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
    ) {
    }
}
