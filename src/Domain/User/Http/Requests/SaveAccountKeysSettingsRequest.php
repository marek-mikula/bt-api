<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\Auth\Validation\ValidateBinanceKeyPair;
use Domain\User\Http\Requests\Data\SaveAccountKeysSettingsRequestData;

class SaveAccountKeysSettingsRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
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

    public function toData(): SaveAccountKeysSettingsRequestData
    {
        return SaveAccountKeysSettingsRequestData::from([
            'publicKey' => (string) $this->input('publicKey'),
            'secretKey' => (string) $this->input('secretKey'),
        ]);
    }
}
