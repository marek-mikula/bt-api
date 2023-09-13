<?php

namespace App\Http\Controllers;

use Domain\Limits\Enums\LimitsNotificationPeriodEnum;
use Domain\Limits\Schedules\CheckMarketCapLimitSchedule;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        CheckMarketCapLimitSchedule::call(period: LimitsNotificationPeriodEnum::MONTHLY);
    }
}
