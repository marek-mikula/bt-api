<?php

namespace App\Http\Controllers\Traits;

use App\Enums\ResponseCodeEnum;
use Illuminate\Http\JsonResponse;

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

    protected function sendJsonResponse(array $data, ResponseCodeEnum $code, string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code->value,
            'data' => $data,
        ], $code->getStatusCode());
    }
}
