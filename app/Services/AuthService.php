<?php

namespace App\Services;

use App\Actions\Auth\CreateDeviceIdentifierAction;
use App\Actions\Auth\CreateRefreshTokenAction;
use App\Http\Requests\Auth\RegisterRequestData;
use App\Data\Auth\TokenPairData;
use App\Enums\MfaTokenTypeEnum;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\User\UserRegisteredNotification;
use App\Notifications\User\UserVerifyDeviceNotification;
use App\Notifications\User\UserVerifyEmailNotification;
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
    public function register(RegisterRequestData $dto): MfaToken
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

    /**
     * Logs in user via credentials
     */
    public function loginWithCredentials(array $credentials, bool $rememberMe = false): TokenPairData|MfaToken|null
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $accessToken = $guard->attempt($credentials);

        if (! $accessToken) {
            return null;
        }

        /** @var User $user */
        $user = $guard->user();

        // user hasn't verified his email yet
        // -> ask for verification via email
        if (! $user->email_verified_at) {
            $mfa = $this->mfaTokenRepository->create($user, MfaTokenTypeEnum::VERIFY_EMAIL);

            $user->notify(new UserVerifyEmailNotification($mfa));

            return $mfa;
        }

        $device = CreateDeviceIdentifierAction::create();

        // user logged in for the first time with current device
        // -> ask for verification via email
        if (! $this->refreshTokenRepository->deviceExists($user, $device)) {
            $mfa = $this->mfaTokenRepository->create($user, MfaTokenTypeEnum::VERIFY_DEVICE, [
                'device' => $device,
            ]);

            $user->notify(new UserVerifyDeviceNotification($mfa));

            return $mfa;
        }

        // delete old refresh tokens for the same device,
        // so they won't duplicate
        $this->refreshTokenRepository->deleteByDevice($user, $device);

        $refreshToken = CreateRefreshTokenAction::create($user, $device, $rememberMe);

        return TokenPairData::from([
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken->refresh_token,
        ]);
    }

    /**
     * Logs in user via model
     */
    public function login(User $user, ?string $device = null): TokenPairData
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $accessToken = $guard->login($user);

        $refreshToken = CreateRefreshTokenAction::create($user, $device);

        return TokenPairData::from([
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken->refresh_token,
        ]);
    }

    /**
     * Refreshes the token and prolongs its validity
     * if valid.
     */
    public function refresh(Request $request): ?string
    {
        $refreshToken = $request->cookie('refreshToken');

        $refreshToken = $this->refreshTokenRepository->findOrFail($refreshToken);

        if ($refreshToken->invalidated || $refreshToken->valid_until->isPast()) {
            return null;
        }

        // prolong the validity of the refresh token with every use
        $this->refreshTokenRepository->prolong($refreshToken);

        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard->refresh();
    }
}
