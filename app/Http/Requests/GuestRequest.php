<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! auth('api')->check();
    }

    public function rules(): array
    {
        return [];
    }
}
