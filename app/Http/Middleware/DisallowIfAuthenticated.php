<?php

namespace App\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DisallowIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($request->expectsJson()) {
                    throw new HttpException(responseCode: ResponseCodeEnum::GUEST_ONLY);
                }

                return redirect('/home');
            }
        }

        return $next($request);
    }
}
