<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    public function boot(): void
    {
        $this->bootObservers();
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    private function bootObservers(): void
    {
        User::observe(UserObserver::class);
    }
}
