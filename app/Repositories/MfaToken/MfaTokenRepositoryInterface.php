<?php

namespace App\Repositories\MfaToken;

use App\Models\MfaToken;
use App\Models\User;
use Domain\Auth\Enums\MfaTokenTypeEnum;

interface MfaTokenRepositoryInterface
{
    public function create(User $user, MfaTokenTypeEnum $type, int $validMinutes = 60): MfaToken;

    public function invalidatePreviousOfType(User $user, MfaTokenTypeEnum $type): void;

    public function find(string $token, MfaTokenTypeEnum $type): ?MfaToken;

    public function findValid(string $token, MfaTokenTypeEnum $type): ?MfaToken;

    public function invalidate(MfaToken $mfaToken): MfaToken;
}
