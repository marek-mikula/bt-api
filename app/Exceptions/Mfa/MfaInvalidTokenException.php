<?php

namespace App\Exceptions\Mfa;

use App\Enums\MfaTokenTypeEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;

class MfaInvalidTokenException extends HttpException
{
    public function __construct(public readonly MfaTokenTypeEnum $type)
    {
        parent::__construct(ResponseCodeEnum::MFA_INVALID_TOKEN, 'Invalid MFA token.');
    }

    public function getData(): array
    {
        return [
            'type' => $this->type->value,
        ];
    }
}
