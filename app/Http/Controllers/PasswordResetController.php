<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\PasswordReset\SendEmailRequest;
use App\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
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
