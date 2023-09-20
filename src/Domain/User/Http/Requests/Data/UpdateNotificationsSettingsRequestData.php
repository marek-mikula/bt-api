<?php

namespace Domain\User\Http\Requests\Data;

use Spatie\LaravelData\Data;

class UpdateNotificationsSettingsRequestData extends Data
{
    public function __construct(
        public readonly bool $whaleEnabled,
    ) {
    }
}
