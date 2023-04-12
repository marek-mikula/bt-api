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
    case INVALID_CREDENTIALS = 10;
    case REFRESH_TOKEN_EXPIRED = 11;
    case MFA_TOKEN = 12;
    case INVALID_OR_MISSING_MFA_TOKEN = 13;
    case INVALID_MFA_CODE = 14;
    case TOKEN_PAIR = 15;
    case ACCESS_TOKEN = 16;

    public function getStatusCode(): int
    {
        return match ($this) {
            self::TOKEN_PAIR,
            self::ACCESS_TOKEN,
            self::MFA_TOKEN,
            self::OK => 200,
            self::INVALID_MFA_CODE,
            self::TOKEN_MISMATCH,
            self::CLIENT_ERROR => 400,
            self::REFRESH_TOKEN_EXPIRED,
            self::INVALID_CREDENTIALS,
            self::UNAUTHENTICATED => 401,
            self::INVALID_OR_MISSING_MFA_TOKEN,
            self::UNAUTHORIZED => 403,
            self::NOT_FOUND => 404,
            self::METHOD_NOT_ALLOWED => 405,
            self::INVALID_CONTENT => 422,
            self::SERVER_ERROR => 500,
        };
    }
}
