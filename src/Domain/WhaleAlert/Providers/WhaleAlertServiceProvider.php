<?php

namespace Domain\WhaleAlert\Providers;

use Domain\WhaleAlert\Console\Commands\CheckWhaleAlertCommand;
use Domain\WhaleAlert\Schedules\CheckWhaleAlertsSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class WhaleAlertServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(WhaleAlertDeferredServiceProvider::class);
        $this->app->register(WhaleAlertRouteServiceProvider::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootCommands();
            $this->bootSchedule();
        }
    }

    private function bootCommands(): void
    {
        $this->commands([
            CheckWhaleAlertCommand::class,
        ]);
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->call(CheckWhaleAlertsSchedule::proxyCall())->everyThirtyMinutes();
        });
    }
}
