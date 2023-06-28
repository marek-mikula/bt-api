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
    case INVALID_REFRESH_TOKEN = 11;
    case MFA_TOKEN = 12;
    case MFA_MISSING_TOKEN = 13;
    case MFA_CORRUPTED_TOKEN = 14;
    case MFA_INVALID_TOKEN = 15;
    case MFA_EXPIRED_TOKEN = 16;
    case MFA_INVALID_CODE = 17;
    case TOKEN_PAIR = 18;
    case ACCESS_TOKEN = 19;

    public function getStatusCode(): int
    {
        return match ($this) {
            // 200
            self::TOKEN_PAIR,
            self::ACCESS_TOKEN,
            self::MFA_TOKEN,
            self::OK => 200,

            // 400
            self::MFA_INVALID_CODE,
            self::TOKEN_MISMATCH,
            self::CLIENT_ERROR => 400,

            // 401
            self::INVALID_REFRESH_TOKEN,
            self::INVALID_CREDENTIALS,
            self::UNAUTHENTICATED => 401,

            // 403
            self::MFA_MISSING_TOKEN,
            self::MFA_CORRUPTED_TOKEN,
            self::MFA_INVALID_TOKEN,
            self::MFA_EXPIRED_TOKEN,
            self::UNAUTHORIZED => 403,

            // 404
            self::NOT_FOUND => 404,

            // 405
            self::METHOD_NOT_ALLOWED => 405,

            // 422
            self::INVALID_CONTENT => 422,

            // 500
            self::SERVER_ERROR => 500,
        };
    }
}
