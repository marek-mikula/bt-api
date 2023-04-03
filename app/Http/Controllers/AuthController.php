<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    private readonly JWTGuard $guard;

    public function __construct()
    {
        /** @var JWTGuard $jwtGuard */
        $jwtGuard = auth('api');

        $this->guard = $jwtGuard;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->getCredentials();

        if (! $token = $this->guard->attempt($credentials)) {
            return $this->sendError(code: ResponseCodeEnum::INVALID_CREDENTIALS, message: 'Invalid credentials.');
        }

        return $this->sendToken($token);
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
        $this->guard->logout();

        return $this->sendSuccess(message: 'Logged out.');
    }

    public function refresh(AuthRequest $request): JsonResponse
    {
        return $this->sendToken($this->guard->refresh());
    }
}
