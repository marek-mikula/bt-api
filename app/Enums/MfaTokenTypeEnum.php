<?php

namespace App\Enums;

enum MfaTokenTypeEnum: int
{
    case VERIFY_EMAIL = 1;
    case VERIFY_DEVICE = 2;
}