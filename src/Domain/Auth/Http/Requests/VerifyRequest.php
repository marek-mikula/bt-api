<?php

namespace Domain\Auth\Http\Requests;

use App\Models\MfaToken;
use Domain\Auth\Services\MfaTokenResolver;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class VerifyRequest extends FormRequest
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
