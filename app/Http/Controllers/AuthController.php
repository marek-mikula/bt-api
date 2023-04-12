<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
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

        $tokenPair = $this->service->loginWithCredentials($credentials);

        if (! $tokenPair) {
            return $this->sendError(code: ResponseCodeEnum::INVALID_CREDENTIALS, message: 'Invalid credentials.');
        }

        return $this->sendTokenPair($tokenPair);
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
            return $this->sendError(code: ResponseCodeEnum::REFRESH_TOKEN_EXPIRED, message: 'Refresh token expired or invalidated.');
        }

        return $this->sendToken($token);
    }
}
