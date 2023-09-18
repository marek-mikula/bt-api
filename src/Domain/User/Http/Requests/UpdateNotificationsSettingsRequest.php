<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\UpdateNotificationsSettingsRequestData;

class UpdateNotificationsSettingsRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'whale.enabled' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function toData(): UpdateNotificationsSettingsRequestData
    {
        return UpdateNotificationsSettingsRequestData::from([
            'whaleEnabled' => $this->boolean('whale.enabled'),
        ]);
    }
}
