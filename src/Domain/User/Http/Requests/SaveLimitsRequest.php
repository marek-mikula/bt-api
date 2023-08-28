<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;

class SaveLimitsRequest extends AuthRequest
{
    public function rules(): array
    {
        return [];
    }
}
