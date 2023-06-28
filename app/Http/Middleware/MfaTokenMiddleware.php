<?php

namespace App\Http\Middleware;

use App\Enums\MfaTokenTypeEnum;
use App\Exceptions\Mfa\MfaCorruptedTokenException;
use App\Exceptions\Mfa\MfaExpiredTokenException;
use App\Exceptions\Mfa\MfaInvalidTokenException;
use App\Exceptions\Mfa\MfaMissingTokenException;
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
     * @throws MfaMissingTokenException
     * @throws MfaCorruptedTokenException
     * @throws MfaInvalidTokenException
     * @throws MfaExpiredTokenException
     */
    public function handle(Request $request, Closure $next, int $type, string $name = 'token'): Response
    {
        $token = $request->get($name);

        $type = MfaTokenTypeEnum::from($type);

        if (empty($token)) {
            throw new MfaMissingTokenException($type);
        }

        try {
            $token = Crypt::decryptString($token);
        } catch (DecryptException) {
            throw new MfaCorruptedTokenException($type);
        }

        $token = $this->mfaTokenRepository->find($token, $type);

        if (! $token) {
            throw new MfaInvalidTokenException($type);
        }

        if ($token->is_expired) {
            throw new MfaExpiredTokenException($type);
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
