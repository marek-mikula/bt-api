<?php

namespace Domain\Auth\Enums;

enum MfaTokenTypeEnum: int
{
    case VERIFY_EMAIL = 1;
    case RESET_PASSWORD = 2;
}
