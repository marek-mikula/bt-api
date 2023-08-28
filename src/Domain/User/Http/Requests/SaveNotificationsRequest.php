<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;

class SaveNotificationsRequest extends AuthRequest
{
    public function rules(): array
    {
        return [];
    }
}
