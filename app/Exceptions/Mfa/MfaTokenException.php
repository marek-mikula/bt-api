<?php

namespace App\Exceptions\Mfa;

use App\Enums\MfaTokenTypeEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;

class MfaTokenException extends HttpException
{
    public function __construct(public readonly MfaTokenTypeEnum $type)
    {
        parent::__construct(ResponseCodeEnum::INVALID_OR_MISSING_MFA_TOKEN, 'Invalid or missing MFA token.');
    }

    public function getData(): array
    {
        return [
            'type' => $this->type->value,
        ];
    }
}
