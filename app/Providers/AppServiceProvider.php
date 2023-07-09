<?php

namespace App\Providers;

use App\Enums\EnvEnum;
use App\Services\AuthService;
use App\Services\Mfa\MfaTokenResolver;
use App\Services\PasswordResetService;
use App\Services\QuizService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        AuthService::class,
        MfaTokenResolver::class,
        PasswordResetService::class,
        QuizService::class,
    ];

    public function register(): void
    {
        Sanctum::ignoreMigrations(); // ignore personal tokens migration

        // register telescope service provider for non-prod only
        if (! $this->app->environment(EnvEnum::PRODUCTION->value)) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        foreach ($this->services as $service) {
            $this->app->singleton($service);
        }
    }

    public function boot(): void
    {
        //
    }
}
