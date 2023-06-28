<?php

namespace App\Exceptions\Mfa;

use App\Enums\MfaTokenTypeEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;

class MfaExpiredTokenException extends HttpException
{
    public function __construct(public readonly MfaTokenTypeEnum $type)
    {
        parent::__construct(ResponseCodeEnum::MFA_EXPIRED_TOKEN, 'Expired MFA token.');
    }

    public function getData(): array
    {
        return [
            'type' => $this->type->value,
        ];
    }
}
