<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\Mfa\MfaVerifyRequest;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class MfaController extends Controller
{
    public function __construct(
        private readonly MfaTokenRepositoryInterface $mfaTokenRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthService $authService,
    ) {
    }

    public function verifyEmail(MfaVerifyRequest $request): JsonResponse
    {
        $token = $request->getToken();
        $code = $request->getCode();

        if ($code !== $token->code) {
            return $this->sendError(code: ResponseCodeEnum::INVALID_MFA_CODE, message: 'Invalid code.');
        }

        $user = $token->loadMissing('user')->user;

        $this->userRepository->verifyEmail($user);

        $this->mfaTokenRepository->invalidate($token);

        $tokenPair = $this->authService->login($user);

        return $this->sendTokenPair($tokenPair);
    }

    public function verifyDevice(MfaVerifyRequest $request): JsonResponse
    {
        $token = $request->getToken();
        $code = $request->getCode();

        if ($code !== $token->code) {
            return $this->sendError(code: ResponseCodeEnum::INVALID_MFA_CODE, message: 'Invalid code.');
        }

        $user = $token->loadMissing('user')->user;
        $device = $token->data['device'];

        if (empty($device)) {
            return $this->sendServerError(message: 'Missing device identifier in token data.');
        }

        $this->mfaTokenRepository->invalidate($token);

        $tokenPair = $this->authService->login($user, $device);

        return $this->sendTokenPair($tokenPair);
    }
}
