<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\GuestRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

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
                new Unique(User::class, 'email'),
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
                Password::min(8)
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

    public function toData(): RegisterRequestData
    {
        return RegisterRequestData::from([
            'firstname' => (string) $this->input('firstname'),
            'lastname' => (string) $this->input('lastname'),
            'email' => (string) $this->input('email'),
            'birthDate' => (string) $this->input('birthDate'),
            'password' => (string) $this->input('password'),
            'publicKey' => (string) $this->input('publicKey'),
            'secretKey' => (string) $this->input('secretKey'),
        ]);
    }
}
