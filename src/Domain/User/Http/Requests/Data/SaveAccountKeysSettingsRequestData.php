<?php

namespace Domain\User\Http\Requests\Data;

use Spatie\LaravelData\Data;

class SaveAccountKeysSettingsRequestData extends Data
{
    public function __construct(
        public readonly string $publicKey,
        public readonly string $secretKey,
    ) {
    }
}
