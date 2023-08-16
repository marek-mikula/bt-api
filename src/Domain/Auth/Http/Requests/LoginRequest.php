<?php

namespace Domain\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
