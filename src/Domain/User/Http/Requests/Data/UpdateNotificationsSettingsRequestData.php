<?php

namespace Domain\User\Http\Requests\Data;

use App\Data\BaseData;

class UpdateNotificationsSettingsRequestData extends BaseData
{
    public function __construct(
        public readonly bool $whaleEnabled,
    ) {
    }
}
