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
            ],
            'rememberMe' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function toData(): LoginRequestData
    {
        return LoginRequestData::from([
            'email' => (string) $this->input('email'),
            'password' => (string) $this->input('password'),
            'rememberMe' => $this->boolean('rememberMe'),
        ]);
    }
}
