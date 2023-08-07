<?php

namespace App\Http\Controllers\Traits;

use App\Enums\ResponseCodeEnum;
use App\Http\Resources\MfaTokenResource;
use App\Models\MfaToken;
use Illuminate\Http\JsonResponse;

trait RespondsAsJson
{
    protected function sendJsonResponse(ResponseCodeEnum $code, array $data = [], array $headers = []): JsonResponse
    {
        return response()->json([
            'code' => $code->name,
            'data' => $data,
        ], $code->getStatusCode(), $headers);
    }

    protected function sendMfaToken(MfaToken $mfaToken): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::MFA_TOKEN, data: [
            'token' => new MfaTokenResource($mfaToken),
        ]);
    }
}
