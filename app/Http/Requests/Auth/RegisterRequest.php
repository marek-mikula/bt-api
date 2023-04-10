<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\GuestRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends GuestRequest
{
    public function rules(): array
    {
        return [
            'firstname' => [
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
            ],
            'birthDate' => [
                'required',
                'string',
                'date',
                'before:today',
            ],
            'password' => [
                'required',
                'string',
                Password::min(9)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'passwordConfirm' => [
                'required',
                'string',
                'same:password',
            ],
            'publicKey' => [
                'required',
                'string',
            ],
            'secretKey' => [
                'required',
                'string',
            ],
        ];
    }
}
