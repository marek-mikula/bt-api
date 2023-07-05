<?php

namespace App\Http\Requests\Auth;

use Spatie\LaravelData\Data;

class LoginRequestData extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $rememberMe,
    ) {
    }
}
