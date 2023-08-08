<?php

namespace App\Repositories\MfaToken;

use App\Actions\CreateUuidTokenAction;
use App\Models\MfaToken;
use App\Models\User;
use Carbon\Carbon;
use Domain\Auth\Enums\MfaTokenTypeEnum;
use Illuminate\Support\Str;

class MfaTokenRepository implements MfaTokenRepositoryInterface
{
    public function create(User $user, MfaTokenTypeEnum $type, int $validMinutes = 60): MfaToken
    {
        // when creating new token, invalidate the previous
        // ones, so they become invalid
        $this->invalidatePreviousOfType($user, $type);

        $token = new MfaToken();
        $token->user_id = $user->id;
        $token->token = CreateUuidTokenAction::create(for: MfaToken::class);
        $token->type = $type;
        $token->valid_until = Carbon::now()->addMinutes($validMinutes);
        $token->code = Str::random(6);

        $token->save();

        return $token;
    }

    public function invalidatePreviousOfType(User $user, MfaTokenTypeEnum $type): void
    {
        MfaToken::query()
            ->where('user_id', '=', $user->id)
            ->where('type', '=', $type->value)
            ->whereNull('invalidated_at')
            ->update([
                'invalidated_at' => Carbon::now(),
            ]);
    }

    public function find(string $token, MfaTokenTypeEnum $type): ?MfaToken
    {
        /** @var MfaToken|null $mfaToken */
        $mfaToken = MfaToken::query()
            ->where('type', '=', $type->value)
            ->where('token', '=', $token)
            ->with('user')
            ->first();

        return $mfaToken;
    }

    public function findValid(string $token, MfaTokenTypeEnum $type): ?MfaToken
    {
        /** @var MfaToken|null $mfaToken */
        $mfaToken = MfaToken::query()
            ->where('type', '=', $type->value)
            ->where('token', '=', $token)
            ->valid()
            ->with('user')
            ->first();

        return $mfaToken;
    }

    public function invalidate(MfaToken $mfaToken): MfaToken
    {
        $mfaToken->invalidated_at = Carbon::now();
        $mfaToken->save();

        return $mfaToken;
    }
}
