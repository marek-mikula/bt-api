<?php

namespace Domain\User\Http\Requests\Data;

use Spatie\LaravelData\Data;

class SaveAccountPasswordRequestData extends Data
{
    public function __construct(
        public readonly string $newPassword,
    ) {
    }
}
