<?php

namespace Domain\Limits\Providers;

use Domain\Limits\Enums\LimitsNotificationPeriodEnum;
use Domain\Limits\Schedules\CheckCryptoLimitSchedule;
use Domain\Limits\Schedules\CheckMarketCapLimitSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class LimitsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(LimitsDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootSchedule();
        }
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            // NUMBER OF CRYPTO LIMITS
            $schedule->call(CheckCryptoLimitSchedule::proxyCall(
                LimitsNotificationPeriodEnum::DAILY
            ))->dailyAt('00:00');

            $schedule->call(CheckCryptoLimitSchedule::proxyCall(
                LimitsNotificationPeriodEnum::WEEKLY
            ))->weeklyOn(1, '00:00'); // 1 = monday

            $schedule->call(CheckCryptoLimitSchedule::proxyCall(
                LimitsNotificationPeriodEnum::MONTHLY
            ))->monthlyOn(1, '00:00'); // 1 = first day of month

            // MARKET CAP LIMITS
            $schedule->call(CheckMarketCapLimitSchedule::proxyCall(
                LimitsNotificationPeriodEnum::DAILY
            ))->dailyAt('00:00');

            // add 1 hour to each schedule incrementally,
            // so the calculation of quotes happens only
            // once, and we prevent concurrency

            $schedule->call(CheckMarketCapLimitSchedule::proxyCall(
                LimitsNotificationPeriodEnum::WEEKLY
            ))->weeklyOn(1, '01:00'); // 1 = monday

            $schedule->call(CheckMarketCapLimitSchedule::proxyCall(
                LimitsNotificationPeriodEnum::MONTHLY
            ))->monthlyOn(1, '02:00'); // 1 = first day of month
        });
    }
}
