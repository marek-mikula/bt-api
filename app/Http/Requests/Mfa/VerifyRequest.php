<?php

namespace App\Http\Requests\Mfa;

use App\Http\Requests\GuestRequest;
use App\Models\MfaToken;
use App\Services\Mfa\MfaTokenResolver;
use Exception;
use Illuminate\Support\Str;

class VerifyRequest extends GuestRequest
{
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'size:6',
            ],
        ];
    }

    public function getCode(): string
    {
        return Str::lower((string) $this->input('code'));
    }

    /**
     * @throws Exception
     */
    public function getToken(): MfaToken
    {
        /** @var MfaTokenResolver $resolver */
        $resolver = app(MfaTokenResolver::class);

        return $resolver->getMfaToken();
    }
}