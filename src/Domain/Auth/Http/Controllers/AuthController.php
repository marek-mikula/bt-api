<?php

namespace Domain\Auth\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\MfaToken;
use Domain\Auth\Http\Requests\LoginRequest;
use Domain\Auth\Http\Requests\RegisterRequest;
use Domain\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public function __construct(
        private readonly AuthService $service
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $mfaToken = $this->service->register($request->toData());

        return $this->sendMfaToken($mfaToken);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->toData();

        $mfaTokenOrUser = $this->service->loginWithCredentials([
            'email' => $data->email,
            'password' => $data->password,
        ], $data->rememberMe);

        // invalid credentials
        if (! $mfaTokenOrUser) {
            return $this->sendJsonResponse(code: ResponseCodeEnum::INVALID_CREDENTIALS);
        }

        // user needs to verify email
        if ($mfaTokenOrUser instanceof MfaToken) {
            return $this->sendMfaToken($mfaTokenOrUser);
        }

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'user' => new UserResource($mfaTokenOrUser),
        ]);
    }

    public function me(AuthRequest $request): JsonResponse
    {
        $user = $request->user('api');

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(AuthRequest $request): JsonResponse
    {
        auth('api')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
