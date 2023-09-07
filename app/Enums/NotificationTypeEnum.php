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
}
