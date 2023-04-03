<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\GuestRequest;

class LoginRequest extends GuestRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ]
        ];
    }

    public function getCredentials(): array
    {
        return [
            'email' => (string) $this->input('email'),
            'password' => (string) $this->input('password'),
        ];
    }
}
