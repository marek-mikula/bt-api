<?php

namespace Domain\Currency\Data;

use App\Data\BaseData;
use App\Data\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;

class NewsData extends BaseData
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $url,
        #[WithCast(
            DateTimeInterfaceCast::class,
            format: Carbon::ATOM,
            type: Carbon::class
        )]
        public readonly Carbon $createdAt,
        #[WithCast(
            DateTimeInterfaceCast::class,
            format: Carbon::ATOM,
            type: Carbon::class
        )]
        public readonly Carbon $publishedAt,
        public readonly string $sourceName,
        public readonly string $sourceUrl,
    ) {
    }
}
