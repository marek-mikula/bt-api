<?php

namespace Domain\Auth\Http\Requests\Data;

use App\Data\BaseData;

class LoginRequestData extends BaseData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $rememberMe,
    ) {
    }
}
