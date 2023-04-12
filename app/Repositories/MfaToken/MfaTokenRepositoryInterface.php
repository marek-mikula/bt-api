<?php

namespace App\Repositories\MfaToken;

use App\Enums\MfaTokenTypeEnum;
use App\Models\MfaToken;
use App\Models\User;

interface MfaTokenRepositoryInterface
{
    public function create(User $user, MfaTokenTypeEnum $type, array $data = [], int $validMinutes = 60): MfaToken;

    public function invalidatePreviousOfType(User $user, MfaTokenTypeEnum $type): void;

    public function findValid(string $token, MfaTokenTypeEnum $type): ?MfaToken;
}
