<?php

namespace App\Http\Requests\Auth;

use App\Data\Casts\CarbonCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class RegisterRequestData extends Data
{
    public function __construct(
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $email,
        #[WithCast(CarbonCast::class)]
        public readonly Carbon $birthDate,
        public readonly string $password,
        public readonly string $publicKey,
        public readonly string $secretKey,
    ) {
    }
}
