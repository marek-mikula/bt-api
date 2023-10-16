<?php

namespace App\Providers;

use Domain\Alert\Providers\AlertServiceProvider;
use Domain\Auth\Providers\AuthServiceProvider;
use Domain\Currency\Providers\CurrencyServiceProvider;
use Domain\Dashboard\Providers\DashboardServiceProvider;
use Domain\Limits\Providers\LimitsServiceProvider;
use Domain\Order\Providers\OrderServiceProvider;
use Domain\Quiz\Providers\QuizServiceProvider;
use Domain\Search\Providers\SearchServiceProvider;
use Domain\User\Providers\UserServiceProvider;
use Domain\WhaleAlert\Providers\WhaleAlertServiceProvider;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(QuizServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
        $this->app->register(SearchServiceProvider::class);
        $this->app->register(AlertServiceProvider::class);
        $this->app->register(LimitsServiceProvider::class);
        $this->app->register(CurrencyServiceProvider::class);
        $this->app->register(WhaleAlertServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
