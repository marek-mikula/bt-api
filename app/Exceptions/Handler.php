<?php

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Traits\RespondsAsJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RespondsAsJson;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'passwordConfirm',
        'publicKey',
        'secretKey',
        'code',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if (! $this->shouldReturnJson($request, $e)) {
            return parent::render($request, $e);
        }

        $isDebug = config('app.debug') === true;

        if ($e instanceof TokenMismatchException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::TOKEN_MISMATCH
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::METHOD_NOT_ALLOWED,
                headers: $e->getHeaders()
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::NOT_FOUND,
                headers: $e->getHeaders()
            );
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::NOT_FOUND,
                data: $isDebug ? [
                    'model' => $e->getModel(),
                    'ids' => $e->getIds(),
                ] : []
            );
        }

        if ($e instanceof AuthenticationException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::UNAUTHENTICATED
            );
        }

        if ($e instanceof AuthorizationException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::UNAUTHORIZED
            );
        }

        if ($e instanceof ValidationException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::INVALID_CONTENT,
                data: [
                    'errors' => $e->errors(),
                ]
            );
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::TOO_MANY_ATTEMPTS,
                headers: $e->getHeaders()
            );
        }

        // common http exception
        if ($e instanceof HttpException) {
            return $this->sendJsonResponse(
                code: $e->getResponseCode(),
                data: $e->getData(),
                headers: $e->getHeaders()
            );
        }

        $data = [
            'reason' => 'Oops. Something went wrong, try again later.',
        ];

        if ($isDebug) {
            $data['reason'] = $e->getMessage();
            $data['trace'] = collect($e->getTrace())
                ->map(static fn (array $trace): string => vsprintf('%s:%s (@%s)', [
                    $trace['file'] ?? $trace['class'] ?? '',
                    $trace['line'] ?? '',
                    $trace['function'],
                ]))
                ->toArray();
        }

        // base http exception from Symphony
        if ($e instanceof BaseHttpException) {
            return $this->sendJsonResponse(
                code: ResponseCodeEnum::SERVER_ERROR,
                data: $data,
                headers: $e->getHeaders()
            );
        }

        // common server error
        return $this->sendJsonResponse(
            code: ResponseCodeEnum::SERVER_ERROR,
            data: $data
        );
    }
}
