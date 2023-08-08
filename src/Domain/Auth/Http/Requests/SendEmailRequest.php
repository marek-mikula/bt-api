<?php

namespace Domain\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
