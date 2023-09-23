<?php

namespace Domain\Currency\Providers;

use Domain\Currency\Console\Commands\SyncCurrenciesCommand;
use Domain\Currency\Schedules\SyncCurrenciesSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CurrencyDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootSchedule();
            $this->bootCommands();
        }
    }

    private function bootCommands(): void
    {
        $this->commands([
            SyncCurrenciesCommand::class,
        ]);
    }

    private function bootSchedule(): void
    {
        $this->callAfterResolving(Schedule::class, static function (Schedule $schedule): void {
            $schedule->call(SyncCurrenciesSchedule::proxyCall())->dailyAt('00:00');
        });
    }
}
