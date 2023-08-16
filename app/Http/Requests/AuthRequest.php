<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function user($guard = null): User
    {
        return once(function () use ($guard): User {
            /** @var User $user */
            $user = parent::user($guard);

            return $user;
        });
    }
}
