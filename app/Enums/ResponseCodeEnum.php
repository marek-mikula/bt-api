<?php

namespace App\Enums;

enum ResponseCodeEnum: int
{
    case OK = 1; // common success
    case CLIENT_ERROR = 2; // common client error
    case SERVER_ERROR = 3; // common server error
    case METHOD_NOT_ALLOWED = 4;
    case TOKEN_MISMATCH = 5;
    case NOT_FOUND = 6;
    case UNAUTHORIZED = 7;
    case UNAUTHENTICATED = 8;
    case INVALID_CONTENT = 9;

    public function getStatusCode(): int
    {
        return match ($this) {
            self::OK => 200,
            self::TOKEN_MISMATCH,
            self::CLIENT_ERROR => 400,
            self::UNAUTHENTICATED => 401,
            self::UNAUTHORIZED => 403,
            self::NOT_FOUND => 404,
            self::METHOD_NOT_ALLOWED => 405,
            self::INVALID_CONTENT => 422,
            self::SERVER_ERROR => 500,
        };
    }
}
