<?php

namespace Domain\User\Http\Requests\Data;

use App\Data\BaseData;

class SaveAccountPasswordSettingsRequestData extends BaseData
{
    public function __construct(
        public readonly string $newPassword,
    ) {
    }
}
