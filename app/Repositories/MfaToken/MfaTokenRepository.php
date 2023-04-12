<?php

namespace App\Repositories\MfaToken;

use App\Actions\Auth\CreateUuidTokenAction;
use App\Enums\MfaTokenTypeEnum;
use App\Models\MfaToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MfaTokenRepository implements MfaTokenRepositoryInterface
{
    public function create(User $user, MfaTokenTypeEnum $type, array $data = [], int $validMinutes = 60): MfaToken
    {
        // when creating new token, invalidate the previous
        // ones, so they become invalid
        $this->invalidatePreviousOfType($user, $type);

        $token = new MfaToken();
        $token->user_id = $user->id;
        $token->token = CreateUuidTokenAction::create(MfaToken::class);
        $token->type = $type;
        $token->data = $data;
        $token->valid_until = Carbon::now()->addMinutes($validMinutes);

        $token->code = Str::upper(Str::random(6));

        $token->save();

        return $token;
    }

    public function invalidatePreviousOfType(User $user, MfaTokenTypeEnum $type): void
    {
        MfaToken::query()
            ->where('user_id', '=', $user->id)
            ->where('type', '=', $type->value)
            ->where('invalidated', '=', false)
            ->update([
                'invalidated' => true,
                'invalidated_at' => Carbon::now(),
            ]);
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
}
