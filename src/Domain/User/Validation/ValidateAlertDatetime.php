<?php

namespace Domain\User\Validation;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateAlertDatetime
{
    /**
     * Validates that the user given date and possibly
     * time is in the future.
     */
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $datetime = $this->getDatetime($data);

        if ($datetime->isFuture()) {
            return;
        }

        $validator->addFailure('date', 'alert_validity');

        if (Arr::get($data, 'time')) {
            $validator->addFailure('time', 'alert_validity');
        }
    }

    private function getDatetime(array $data): Carbon
    {
        $date = (string) Arr::get($data, 'date');
        $time = Arr::has($data, 'time') ? (string) Arr::get($data, 'time') : null;

        if (empty($time)) {
            return Carbon::createFromFormat('Y-m-d', $date)
                ->startOfDay();
        }

        return Carbon::createFromFormat('Y-m-d H:i', "{$date} {$time}")
            ->setSeconds(0);
    }
}
