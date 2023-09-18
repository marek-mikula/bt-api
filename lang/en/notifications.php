<?php

use App\Enums\NotificationTypeEnum;

return [
    // USER

    NotificationTypeEnum::EMAIL_VERIFIED->value => [
        'mail' => [
            'subject' => 'Email address verified',
            'body' => [
                'line1' => 'You have successfully verified your email address. You can now proceed to the application.',
            ],
        ],
        'database' => [
            'title' => 'Email address verified',
            'body' => 'Your email address was successfully verified.',
        ],
    ],
    NotificationTypeEnum::NEW_DEVICE->value => [
        'mail' => [
            'subject' => 'Login from new device',
            'body' => [
                'line1' => 'We noticed a recent login to your account from a new device. Here are the details:',
                'list1' => 'Time: **:time**',
                'list2' => 'IP Address: **:ipAddress**',
                'list3' => 'Browser: **:browser**',
                'line2' => 'If you recognize this activity, no further action is required. However, if you didn\'t initiate this login or suspect unauthorized access, please take immediate action and change your account password.',
            ],
        ],
        'database' => [
            'title' => 'Login from new device',
            'body' => 'There was a new activity detected in your profile from IP address :ipAddress with browser :browser at :time.',
        ],
    ],
    NotificationTypeEnum::PASSWORD_CHANGED->value => [
        'mail' => [
            'subject' => 'Password changed',
            'body' => [
                'line1' => 'We are writing to inform you that your password has been successfully changed.',
                'line2' => 'If you changed your password recently, then you can safely ignore this email.',
                'line3' => 'However, if you did not make this change, or you believe that someone else may have accessed your account, please contact us immediately.',
            ],
        ],
        'database' => [
            'title' => 'Password changed',
            'body' => 'Your password has been successfully changed.',
        ],
    ],
    NotificationTypeEnum::REGISTERED->value => [
        'mail' => [
            'subject' => 'Account created',
            'body' => [
                'line1' => 'Thank you for registering with our web application! To complete the registration process, we need to confirm your email address.',
                'line2' => 'Please follow the link bellow and use the following code to confirm your email address: **:code**.',
                'action1' => 'Confirm email address',
                'line3' => 'The link is valid until **:validity**.',
                'line4' => 'If you did not initiate this registration or do not recognize this email, please disregard this message.',
            ],
        ],
    ],
    NotificationTypeEnum::RESET_PASSWORD->value => [
        'mail' => [
            'subject' => 'Password reset request',
            'body' => [
                'line1' => 'We received a request to reset the password associated with your account. To proceed with the password reset process, please click on the link below:',
                'action1' => 'Reset password',
                'line2' => 'When you click on the link, you\'ll be taken to a page where you can enter the following secret code: **:code**.',
                'line3' => 'The link is valid until **:validity**.',
                'line4' => 'Once you\'ve entered the secret code, you\'ll be able to reset your password and access your account.',
                'line5' => 'If you did not request a password reset, please ignore this email. Someone may have entered your email address by mistake. Please do not click on the link or share the secret code with anyone.',
            ],
        ],
    ],
    NotificationTypeEnum::VERIFY_EMAIL->value => [
        'mail' => [
            'subject' => 'Email address verification',
            'body' => [
                'line1' => 'We require you to verify your email address in order to complete the login process. This is a security measure that ensures only you have access to your account.',
                'line2' => 'Please follow the link bellow and use the following code to complete the verification process: **:code**.',
                'action1' => 'Verify email address',
                'line3' => 'The link is valid until **:validity**.',
                'line4' => 'If you did not initiate this login or believe that your account may be compromised, please contact us immediately.',
                'line5' => 'Thank you for your cooperation in keeping your account secure.',
            ],
        ],
    ],
    NotificationTypeEnum::ASSETS_SYNCED->value => [
        'database' => [
            'title' => 'Your assets have been successfully synchronized with Binance.',
        ],
    ],

    // ALERTS

    NotificationTypeEnum::ALERT->value => [
        'mail' => [
            'subject' => 'Alert - :title',
            'body' => [
                'line1' => 'We would like to inform you that your requested alert ":title" just got triggered.',
            ],
        ],
        'database' => [
            'title' => ':title',
            'body' => ':content',
        ],
    ],

    // LIMITS

    NotificationTypeEnum::CRYPTOCURRENCY_MIN->value => [
        'database' => [
            'title' => 'Your min. cryptocurrency limit has been exceeded!',
            'body' => 'Your set min. cryptocurrency limit of :limit has been exceeded by :by. Consider buying some new assets to bring the value back into the set range of your limits.',
        ],
    ],
    NotificationTypeEnum::CRYPTOCURRENCY_MAX->value => [
        'database' => [
            'title' => 'Your max. cryptocurrency limit has been exceeded!',
            'body' => 'Your set max. cryptocurrency limit of :limit has been exceeded by :by. Consider selling some of your assets to bring the value back into the set range of your limits.',
        ],
    ],
    NotificationTypeEnum::MARKET_CAP->value => [
        'database' => [
            'title' => 'Your market cap limit has been exceeded!',
            'body' => 'Your set range between :from % and :to % for :category has been exceeded by :by %. Consider selling or buying some assets from this category to bring the value back into the set range of your limits.',
        ],
    ],

    // WHALE ALERTS

    NotificationTypeEnum::WHALE_ALERT->value => [
        'database' => [
            'title' => 'Whale alert on currency :currencySymbol!',
            'body' => 'There have been total of :n transactions over $1,000,000 on currency :currency (:currencySymbol) with total value of :amount ($:amountUsd).',
        ],
    ],
];
