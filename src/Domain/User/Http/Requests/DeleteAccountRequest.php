<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;

class DeleteAccountRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'current_password',
            ],
        ];
    }
}
