<?php

namespace Domain\Alert\Providers;

use Domain\Alert\Schedules\CheckAlertsSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AlertServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(AlertDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootSchedule();
        }

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'alert');
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, static function (Schedule $schedule): void {
            $schedule->call(CheckAlertsSchedule::proxyCall())->everyMinute();
        });
    }
}
