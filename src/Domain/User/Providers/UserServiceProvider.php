<?php

namespace Domain\User\Providers;

use Domain\User\Console\Commands\SyncAssetsCommand;
use Domain\User\Schedules\SyncAssetsSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(UserDeferredServiceProvider::class);
        $this->app->register(UserRouteServiceProvider::class);
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
            SyncAssetsCommand::class,
        ]);
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->call(SyncAssetsSchedule::proxyCall())->everyFifteenMinutes();
        });
    }
}
