<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    // user domain
    case EMAIL_VERIFIED = 'user@email-verified';
    case NEW_DEVICE = 'user@new-device';
    case PASSWORD_CHANGED = 'user@password-changed';
    case REGISTERED = 'user@registered';
    case RESET_PASSWORD = 'user@reset-password';
    case VERIFY_EMAIL = 'user@verify-email';
    case ASSETS_SYNCED = 'user@assets-synced';

    // alert domain
    case ALERT = 'alert@alert';

    // limits domain
    case CRYPTOCURRENCY_MIN = 'limits@cryptocurrency-min';
    case CRYPTOCURRENCY_MAX = 'limits@cryptocurrency-max';
    case MARKET_CAP = 'limits@market-cap';

    // whale alert domain
    case WHALE_ALERT = 'whaleAlert@alert';
}
