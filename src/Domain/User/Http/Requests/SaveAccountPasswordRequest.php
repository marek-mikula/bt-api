<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\SaveAccountPasswordRequestData;
use Illuminate\Validation\Rules\Password;

class SaveAccountPasswordRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'oldPassword' => [
                'required',
                'string',
                'current_password:api',
            ],
            'newPassword' => [
                'required',
                'string',
                Password::min(8)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'newPasswordConfirm' => [
                'required',
                'string',
                'same:newPassword',
            ],
        ];
    }

    public function toData(): SaveAccountPasswordRequestData
    {
        return SaveAccountPasswordRequestData::from([
            'newPassword' => (string) $this->input('newPassword'),
        ]);
    }
}
