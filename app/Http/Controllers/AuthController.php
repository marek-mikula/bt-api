<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\MfaToken;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $service
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $mfaToken = $this->service->register($request->toDTO());

        return $this->sendMfa($mfaToken);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->getCredentials();

        $tokenPairOfMfaToken = $this->service->loginWithCredentials($credentials);

        if (! $tokenPairOfMfaToken) {
            return $this->sendError(code: ResponseCodeEnum::INVALID_CREDENTIALS, message: 'Invalid credentials.');
        }

        // user needs to verify device
        if ($tokenPairOfMfaToken instanceof MfaToken) {
            return $this->sendMfa($tokenPairOfMfaToken);
        }

        return $this->sendTokenPair($tokenPairOfMfaToken);
    }

    public function me(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        return $this->sendSuccess([
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(AuthRequest $request): JsonResponse
    {
        /** @var JWTGuard $jwtGuard */
        $jwtGuard = auth('api');

        $jwtGuard->logout();

        return $this->sendSuccess(message: 'Logged out.');
    }

    public function refresh(AuthRequest $request): JsonResponse
    {
        $token = $this->service->refresh($request);

        if (! $token) {
            return $this->sendError(code: ResponseCodeEnum::INVALID_REFRESH_TOKEN, message: 'Invalid refresh token.');
        }

        return $this->sendToken($token);
    }
}
