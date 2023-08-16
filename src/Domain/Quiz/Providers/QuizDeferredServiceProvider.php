<?php

namespace Domain\Quiz\Providers;

use Domain\Auth\Services\AuthService;
use Domain\Auth\Services\MfaTokenResolver;
use Domain\Auth\Services\PasswordResetService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class QuizDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        AuthService::class,
        MfaTokenResolver::class,
        PasswordResetService::class,
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
