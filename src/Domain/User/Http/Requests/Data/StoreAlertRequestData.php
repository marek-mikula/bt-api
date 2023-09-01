<?php

namespace Domain\User\Http\Requests\Data;

use App\Data\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class StoreAlertRequestData extends Data
{
    public function __construct(
        #[WithCast(
            DateTimeInterfaceCast::class,
            format: 'Y-m-d',
            type: Carbon::class
        )]
        public readonly Carbon $date,
        #[WithCast(
            DateTimeInterfaceCast::class,
            format: 'H:i',
            type: Carbon::class,
            nullable: true
        )]
        public readonly ?Carbon $time,
        public readonly string $content,
    ) {
    }
}
