<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;

class TokenPairData extends Data
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
    ) {
    }
}
