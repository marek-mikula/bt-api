<?php

namespace Domain\Auth\Services;

use App\Http\Requests\AuthRequest;
use App\Models\MfaToken;
use App\Models\User;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Domain\Auth\Enums\MfaTokenTypeEnum;
use Domain\Auth\Http\Requests\Data\RegisterRequestData;
use Domain\Auth\Notifications\RegisteredNotification;
use Domain\Auth\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Hashing\Hasher;

class AuthService
{
    public function __construct(
        private readonly MfaTokenRepositoryInterface $mfaTokenRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly Hasher $hasher,
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
            'birth_date' => $dto->birthDate->toDateTimeString(),
            'email' => $dto->email,
            'password' => $dto->password,
            'public_key' => $dto->publicKey,
            'secret_key' => $dto->secretKey,
        ];

        $user = $this->userRepository->create($data);

        $mfa = $this->mfaTokenRepository->create($user, MfaTokenTypeEnum::VERIFY_EMAIL);

        // notify user
        $user->notify(new RegisteredNotification($mfa));

        return $mfa;
    }

    /**
     * Logs in user via credentials
     */
    public function loginWithCredentials(array $credentials, bool $rememberMe = false): MfaToken|User|null
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        // check if user exists
        if (! $user) {
            return null;
        }

        // check if user's password matches
        if (! $this->hasher->check($credentials['password'], $user->password)) {
            return null;
        }

        // user needs to verify his email
        if (! $user->email_verified_at) {
            $mfa = $this->mfaTokenRepository->create($user, MfaTokenTypeEnum::VERIFY_EMAIL);

            $user->notify(new VerifyEmailNotification($mfa));

            return $mfa;
        }

        $this->loginWithModel($user, $rememberMe);

        return $user;
    }

    /**
     * Logs in user via model
     */
    public function loginWithModel(User $user, bool $rememberMe = false): void
    {
        auth('api')->login($user, $rememberMe);
    }

    public function logout(AuthRequest $request): void
    {
        auth('api')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
