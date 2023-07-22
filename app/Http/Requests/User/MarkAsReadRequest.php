<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AuthRequest;
use App\Models\User;

class MarkAsReadRequest extends AuthRequest
{
    public function authorize(): bool
    {
        if (! parent::authorize()) {
            return false;
        }

        /** @var User $user */
        $user = $this->user('api');

        return $user->notifications()
            ->whereNull('read_at')
            ->whereKey($this->getUuid())
            ->exists();
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'string',
                'uuid',
            ],
        ];
    }

    public function getUuid(): string
    {
        return $this->string('uuid');
    }
}
