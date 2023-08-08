<?php

namespace Domain\Quiz\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizMiddleware
{
    /**
     * @throws Exception
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('api')->check()) {
            throw new Exception('Quiz middleware must be used with "auth middleware."');
        }

        /** @var User $user */
        $user = $request->user('api');

        if ($user->quiz_taken) {
            throw new HttpException(responseCode: ResponseCodeEnum::QUIZ_TAKEN);
        }

        return $next($request);
    }
}
