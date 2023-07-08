<?php

namespace App\Exceptions\Mfa;

use App\Enums\MfaTokenTypeEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;

class MfaMissingTokenException extends HttpException
{
    public function __construct(public readonly MfaTokenTypeEnum $type)
    {
        parent::__construct(ResponseCodeEnum::MFA_MISSING_TOKEN, 'Missing MFA token.');
    }

    public function getData(): array
    {
        return [
            'type' => $this->type->value,
        ];
    }
}
