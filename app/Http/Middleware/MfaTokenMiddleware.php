<?php

namespace App\Http\Middleware;

use App\Enums\MfaTokenTypeEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Services\Mfa\MfaTokenResolver;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class MfaTokenMiddleware
{
    public function __construct(
        private readonly MfaTokenRepositoryInterface $mfaTokenRepository,
        private readonly MfaTokenResolver $mfaTokenResolver,
    ) {
    }

    /**
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next, int $type, string $name = 'token'): Response
    {
        $token = $request->get($name);

        $type = MfaTokenTypeEnum::from($type);

        if (empty($token)) {
            throw new HttpException(responseCode: ResponseCodeEnum::MFA_MISSING_TOKEN);
        }

        try {
            $token = Crypt::decryptString($token);
        } catch (DecryptException) {
            throw new HttpException(responseCode: ResponseCodeEnum::MFA_CORRUPTED_TOKEN, data: [
                'type' => $type->value,
            ]);
        }

        $token = $this->mfaTokenRepository->find($token, $type);

        if (! $token) {
            throw new HttpException(responseCode: ResponseCodeEnum::MFA_INVALID_TOKEN, data: [
                'type' => $type->value,
            ]);
        }

        if ($token->is_expired) {
            throw new HttpException(responseCode: ResponseCodeEnum::MFA_EXPIRED_TOKEN, data: [
                'type' => $type->value,
            ]);
        }

        // set token model to the resolver, so we don't have to
        // query it multiple times
        $this->mfaTokenResolver->setMfaToken($token);

        return $next($request);
    }

    /**
     * Returns string, which can be used in route definition
     */
    public static function apply(MfaTokenTypeEnum $type, string $name = 'token'): string
    {
        return "mfa:{$type->value},{$name}";
    }
}
