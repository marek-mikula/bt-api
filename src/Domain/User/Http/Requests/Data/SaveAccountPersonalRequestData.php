<?php

namespace Domain\User\Http\Requests\Data;

use App\Data\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class SaveAccountPersonalRequestData extends Data
{
    public function __construct(
        public readonly string $firstname,
        public readonly string $lastname,
        #[WithCast(
            DateTimeInterfaceCast::class,
            format: 'Y-m-d',
            type: Carbon::class
        )]
        public readonly Carbon $birthDate,
    ) {
    }
}
