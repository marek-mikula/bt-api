<?php

namespace Domain\Auth\Http\Requests\Data;

use App\Data\BaseData;
use App\Data\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;

class RegisterRequestData extends BaseData
{
    public function __construct(
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $email,
        #[WithCast(
            DateTimeInterfaceCast::class,
            format: 'Y-m-d',
            type: Carbon::class
        )]
        public readonly Carbon $birthDate,
        public readonly string $password,
        public readonly string $publicKey,
        public readonly string $secretKey,
    ) {
    }
}
