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
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        'current_password',
        'password',
        'password_confirmation',
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
                data: [],
                code: ResponseCodeEnum::TOKEN_MISMATCH,
                message: 'CSRF token mismatch.'
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->sendJsonResponse(
                data: [],
                code: ResponseCodeEnum::METHOD_NOT_ALLOWED,
                message: 'The specified method for the request is invalid.'
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->sendJsonResponse(
                data: [],
                code: ResponseCodeEnum::NOT_FOUND,
                message: 'The specified URL cannot be found.'
            );
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->sendJsonResponse(
                data: [
                    'model' => $e->getModel(),
                    'ids' => $e->getIds(),
                ],
                code: ResponseCodeEnum::NOT_FOUND,
                message: 'Model not found.'
            );
        }

        if ($e instanceof AuthenticationException) {
            return $this->sendJsonResponse(
                data: [],
                code: ResponseCodeEnum::UNAUTHENTICATED,
                message: 'Unauthenticated.'
            );
        }

        if ($e instanceof AuthorizationException) {
            return $this->sendJsonResponse(
                data: [],
                code: ResponseCodeEnum::UNAUTHORIZED,
                message: 'Unauthorized.'
            );
        }

        if ($e instanceof ValidationException) {
            return $this->sendJsonResponse(
                data: [
                    'errors' => $e->errors(),
                ],
                code: ResponseCodeEnum::INVALID_CONTENT,
                message: 'Invalid data.'
            );
        }

        // common http exception
        if ($e instanceof HttpException) {
            return $this->sendJsonResponse(
                data: $e->getData(),
                code: $e->getResponseCode(),
                message: $e->getMessage()
            );
        }

        $data = [];

        if ($isDebug) {
            $data = collect($e->getTrace())
                ->map(static fn (array $trace): string => vsprintf('%s:%s (@%s)', [
                    $trace['file'] ?? $trace['class'] ?? '',
                    $trace['line'] ?? '',
                    $trace['function'],
                ]))
                ->toArray();
        }

        return $this->sendJsonResponse(
            data: $data,
            code: ResponseCodeEnum::SERVER_ERROR,
            message: $isDebug ? $e->getMessage() : 'Oops. Something went wrong, try again later.'
        );
    }
}
