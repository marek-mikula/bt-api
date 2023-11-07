<?php

namespace Domain\User\Providers;

use Domain\User\Services\UserAccountSettingsService;
use Domain\User\Services\UserAlertsSettingsService;
use Domain\User\Services\UserLimitsSettingsService;
use Domain\User\Services\UserService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        UserService::class,
        UserAccountSettingsService::class,
        UserAlertsSettingsService::class,
        UserLimitsSettingsService::class,
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
