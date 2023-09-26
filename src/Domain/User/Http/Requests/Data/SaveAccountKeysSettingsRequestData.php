<?php

namespace Domain\User\Http\Requests\Data;

use App\Data\BaseData;

class SaveAccountKeysSettingsRequestData extends BaseData
{
    public function __construct(
        public readonly string $publicKey,
        public readonly string $secretKey,
    ) {
    }
}
