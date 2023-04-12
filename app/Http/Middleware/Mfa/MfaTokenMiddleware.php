<?php

namespace App\Http\Middleware\Mfa;

use App\Enums\MfaTokenTypeEnum;
use App\Exceptions\Mfa\MfaTokenException;
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
     * @throws MfaTokenException
     */
    public function handle(Request $request, Closure $next, int $type, string $name = 'token'): Response
    {
        $token = $request->get($name);

        $type = MfaTokenTypeEnum::from($type);

        if (empty($token)) {
            throw new MfaTokenException($type);
        }

        try {
            $token = Crypt::decryptString($token);
        } catch (DecryptException) {
            throw new MfaTokenException($type);
        }

        $token = $this->mfaTokenRepository->findValid($token, $type);

        if (! $token) {
            throw new MfaTokenException($type);
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
