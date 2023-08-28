<?php

namespace Domain\Auth\Http\Requests;

use App\Models\User;
use Domain\Auth\Http\Requests\Data\RegisterRequestData;
use Domain\Auth\Validation\ValidateBinanceKeyPair;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class RegisterRequest extends FormRequest
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

    public function after(): array
    {
        return [
            app(ValidateBinanceKeyPair::class),
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
