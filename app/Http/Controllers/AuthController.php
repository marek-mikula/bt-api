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
use Illuminate\Http\RedirectResponse;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

class AuthController extends Controller
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

    public function csrfCookie(): RedirectResponse
    {
        return redirect()->action([CsrfCookieController::class, 'show']);
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
        /** @var User $user */
        $user = $request->user('api');

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
