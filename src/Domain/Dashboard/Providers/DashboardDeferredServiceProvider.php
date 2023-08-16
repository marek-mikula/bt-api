<?php

namespace Domain\Dashboard\Providers;

use Domain\Dashboard\Cache\DashboardCache;
use Domain\Dashboard\Services\DashboardService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DashboardDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        DashboardCache::class,
        DashboardService::class,
    ];

    public function register(): void
    {
        foreach ($this->services as $service) {
            $this->app->singleton($service);
        }
    }

    /**
     * @return list<class-string>
     */
    public function provides(): array
    {
        return array_merge(
            [],
            $this->services,
        );
    }
}
