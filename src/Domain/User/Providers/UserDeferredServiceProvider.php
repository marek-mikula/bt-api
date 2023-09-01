<?php

namespace Domain\User\Providers;

use Domain\User\Services\UserAccountSettingsService;
use Domain\User\Services\UserAlertsSettingsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        UserAccountSettingsService::class,
        UserAlertsSettingsService::class,
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
            $this->services
        );
    }
}
