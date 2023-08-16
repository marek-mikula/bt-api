<?php

namespace Domain\Auth\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Auth\Http\Requests\SendEmailRequest;
use Domain\Auth\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends ApiController
{
    public function __construct(private readonly PasswordResetService $service)
    {
    }

    public function sendEmail(SendEmailRequest $request): JsonResponse
    {
        $this->service->sendEmail($request->getEmail());

        // send success every time even though the user
        // does not exist, so the user cannot farm email
        // addresses in the system
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
