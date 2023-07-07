<?php

namespace App\Http\Requests\PasswordReset;

use App\Http\Requests\GuestRequest;

class SendEmailRequest extends GuestRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
        ];
    }

    public function getEmail(): string
    {
        return (string) $this->input('email');
    }
}
