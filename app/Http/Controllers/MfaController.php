<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\Mfa\ResetPasswordRequest;
use App\Http\Requests\Mfa\VerifyRequest;
use App\Notifications\User\EmailVerifiedNotification;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;

class MfaController extends Controller
{
    public function __construct(
        private readonly MfaTokenRepositoryInterface $mfaTokenRepository,
        private readonly PasswordResetService $passwordResetService,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function verifyEmail(VerifyRequest $request): JsonResponse
    {
        $token = $request->getToken();
        $code = $request->getCode();

        if ($code !== $token->code) {
            return $this->sendJsonResponse(code: ResponseCodeEnum::MFA_INVALID_CODE);
        }

        $user = $token->loadMissing('user')->user;

        $this->userRepository->verifyEmail($user);

        $this->mfaTokenRepository->invalidate($token);

        // notify user
        $user->notify(new EmailVerifiedNotification());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $token = $request->getToken();
        $code = $request->getCode();

        if ($code !== $token->code) {
            return $this->sendJsonResponse(code: ResponseCodeEnum::MFA_INVALID_CODE);
        }

        $user = $token->loadMissing('user')->user;

        $this->mfaTokenRepository->invalidate($token);

        $this->passwordResetService->resetPassword($user, $request->password());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
