<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    // user notifications
    case EMAIL_VERIFIED = 'email-verified';
    case NEW_DEVICE = 'new-device';
    case PASSWORD_CHANGED = 'password-changed';
    case REGISTERED = 'registered';
    case RESET_PASSWORD = 'reset-password';
    case VERIFY_EMAIL = 'verify-email';
}
