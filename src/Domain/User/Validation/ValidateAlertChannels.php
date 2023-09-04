<?php

namespace Domain\User\Validation;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateAlertChannels
{
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $asMail = (bool) Arr::get($data, 'asMail', false);
        $asNotification = (bool) Arr::get($data, 'asNotification', false);

        if ($asMail || $asNotification) {
            return;
        }

        $validator->addFailure('asMail', 'alert_channel');
        $validator->addFailure('asNotification', 'alert_channel');
    }
}
