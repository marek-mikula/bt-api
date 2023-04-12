<?php

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;
use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;

class HttpException extends BaseHttpException
{
    public function __construct(private readonly ResponseCodeEnum $responseCode, string $message)
    {
        parent::__construct($this->responseCode->getStatusCode(), $message);
    }

    public function getResponseCode(): ResponseCodeEnum
    {
        return $this->responseCode;
    }

    public function getData(): array
    {
        return [];
    }
}
