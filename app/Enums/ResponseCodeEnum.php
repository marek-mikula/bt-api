<?php

namespace App\Enums;

enum ResponseCodeEnum
{
    case OK; // common success
    case CLIENT_ERROR; // common client error
    case SERVER_ERROR; // common server error
    case METHOD_NOT_ALLOWED;
    case TOKEN_MISMATCH;
    case NOT_FOUND;
    case UNAUTHORIZED;
    case UNAUTHENTICATED;
    case INVALID_CONTENT;
    case INVALID_CREDENTIALS;
    case MFA_TOKEN;
    case MFA_MISSING_TOKEN;
    case MFA_CORRUPTED_TOKEN;
    case MFA_INVALID_TOKEN;
    case MFA_EXPIRED_TOKEN;
    case MFA_INVALID_CODE;
    case QUIZ_TAKEN;
    case TOO_MANY_ATTEMPTS;
    case GUEST_ONLY;
    case LIMITS_LOCKED;

    public function getStatusCode(): int
    {
        return match ($this) {
            // 200
            self::MFA_TOKEN,
            self::OK => 200,

            // 400
            self::MFA_INVALID_CODE,
            self::TOKEN_MISMATCH,
            self::CLIENT_ERROR => 400,

            // 401
            self::INVALID_CREDENTIALS,
            self::UNAUTHENTICATED => 401,

            // 403
            self::QUIZ_TAKEN,
            self::MFA_MISSING_TOKEN,
            self::MFA_CORRUPTED_TOKEN,
            self::MFA_INVALID_TOKEN,
            self::MFA_EXPIRED_TOKEN,
            self::GUEST_ONLY,
            self::LIMITS_LOCKED,
            self::UNAUTHORIZED => 403,

            // 404
            self::NOT_FOUND => 404,

            // 405
            self::METHOD_NOT_ALLOWED => 405,

            // 422
            self::INVALID_CONTENT => 422,

            // 429
            self::TOO_MANY_ATTEMPTS => 429,

            // 500
            self::SERVER_ERROR => 500,
        };
    }
}
