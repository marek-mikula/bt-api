<?php

namespace App\Http\Middleware;

use App\Exceptions\QuizException;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizMiddleware
{
    /**
     * @throws Exception
     * @throws QuizException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('api')->check()) {
            throw new Exception('Quiz middleware must be used with "auth middleware."');
        }

        /** @var User $user */
        $user = $request->user('api');

        if ($user->quiz_taken) {
            throw new QuizException();
        }

        return $next($request);
    }
}
