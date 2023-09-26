<?php

namespace Domain\User\Http\Requests\Data;

use App\Data\BaseData;
use App\Data\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;

class StoreAlertRequestData extends BaseData
{
    public function __construct(
        public readonly string $title,
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
        public readonly ?string $content,
        public readonly bool $asMail,
        public readonly bool $asNotification,
    ) {
    }
}
