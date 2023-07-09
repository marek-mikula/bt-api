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

        return $this->sendMfa($mfaToken);
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
            return $this->sendError(code: ResponseCodeEnum::INVALID_CREDENTIALS, message: 'Invalid credentials.');
        }

        // user needs to verify email
        if ($mfaTokenOrUser instanceof MfaToken) {
            return $this->sendMfa($mfaTokenOrUser);
        }

        return $this->sendSuccess([
            'user' => new UserResource($mfaTokenOrUser),
        ]);
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
        auth('api')->logout();

        return $this->sendSuccess(message: 'Logged out.');
    }
}
