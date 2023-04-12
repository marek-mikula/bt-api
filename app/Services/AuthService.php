<?php

namespace App\Services;

use App\Actions\Auth\CreateRefreshTokenAction;
use App\DTOs\Auth\RegisterRequestDTO;
use App\DTOs\Auth\TokenPair;
use App\Enums\MfaTokenTypeEnum;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\User\UserRegisteredNotification;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\RefreshToken\RefreshTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;

class AuthService
{
    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly MfaTokenRepositoryInterface $mfaTokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * Registers user, sends registered notification and
     * generates new MFA token, so he can verify his email
     * address.
     */
    public function register(RegisterRequestDTO $dto): MfaToken
    {
        $data = [
            'firstname' => $dto->firstname,
            'lastname' => $dto->lastname,
            'email' => $dto->email,
            'password' => $dto->password,
            'public_key' => $dto->publicKey,
            'secret_key' => $dto->secretKey,
        ];

        $user = $this->userRepository->create($data);

        $mfa = $this->mfaTokenRepository->create($user, MfaTokenTypeEnum::VERIFY_EMAIL);

        // notify user
        $user->notify(new UserRegisteredNotification($mfa));

        return $mfa;
    }

    public function login(User $user): TokenPair
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $accessToken = $guard->login($user);

        $refreshToken = CreateRefreshTokenAction::create($user);

        return TokenPair::from([
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken->refresh_token,
        ]);
    }

    public function loginWithCredentials(array $credentials): ?TokenPair
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $accessToken = $guard->attempt($credentials);

        if (! $accessToken) {
            return null;
        }

        /** @var User $user */
        $user = $guard->user();

        $refreshToken = CreateRefreshTokenAction::create($user);

        return TokenPair::from([
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken->token,
        ]);
    }

    public function refresh(Request $request): ?string
    {
        $refreshToken = $request->cookie('refreshToken');

        $refreshToken = $this->refreshTokenRepository->findOrFail($refreshToken);

        if ($refreshToken->invalidated || $refreshToken->valid_until->isPast()) {
            return null;
        }

        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard->refresh();
    }
}
