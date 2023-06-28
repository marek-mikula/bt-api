<?php

namespace App\Http\Controllers\Traits;

use App\DTOs\Auth\TokenPair;
use App\Enums\ResponseCodeEnum;
use App\Models\MfaToken;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\JWTGuard;

trait RespondsAsJson
{
    protected function sendSuccess(array $data = [], ResponseCodeEnum $code = ResponseCodeEnum::OK, string $message = 'Success.'): JsonResponse
    {
        return $this->sendJsonResponse($data, $code, $message);
    }

    protected function sendError(array $data = [], ResponseCodeEnum $code = ResponseCodeEnum::CLIENT_ERROR, string $message = 'Error.'): JsonResponse
    {
        return $this->sendJsonResponse($data, $code, $message);
    }

    protected function sendMfa(MfaToken $mfaToken): JsonResponse
    {
        return $this->sendSuccess([
            'token' => [
                'type' => $mfaToken->type->value,
                'token' => $mfaToken->secret_token,
                'validUntil' => $mfaToken->valid_until->toIso8601String(),
            ],
        ], ResponseCodeEnum::MFA_TOKEN);
    }

    protected function sendTokenPair(TokenPair $tokenPair): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $expiresIn = $guard->factory()->getTTL() * 60;

        return $this->sendSuccess([
            'token' => [
                'type' => 'Bearer',
                'accessToken' => $tokenPair->accessToken,
                'refreshToken' => $tokenPair->refreshToken,
                'expiresIn' => $expiresIn,
            ],
        ], ResponseCodeEnum::TOKEN_PAIR);
    }

    protected function sendToken(string $token): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $expiresIn = $guard->factory()->getTTL() * 60;

        return $this->sendSuccess([
            'token' => [
                'type' => 'Bearer',
                'accessToken' => $token,
                'expiresIn' => $expiresIn,
            ],
        ], ResponseCodeEnum::ACCESS_TOKEN);
    }

    protected function sendJsonResponse(array $data, ResponseCodeEnum $code, string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code->value,
            'data' => $data,
        ], $code->getStatusCode());
    }
}
