<?php

namespace Domain\Auth\Http\Requests;

use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends VerifyRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
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
        ]);
    }

    public function password(): string
    {
        return (string) $this->input('password');
    }
}
