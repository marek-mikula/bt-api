<?php

namespace Domain\Alert\Providers;

use Domain\Alert\Console\Commands\AlertCheckCommand;
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
            $this->bootCommands();
            $this->bootSchedule();
        }
    }

    private function bootCommands(): void
    {
        $this->commands([
            AlertCheckCommand::class,
        ]);
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('alert:check')->everyMinute();
        });
    }
}
