<?php

namespace App\Providers;

use App\Enums\EnvEnum;
use App\Services\AuthService;
use App\Services\Mfa\MfaTokenResolver;
use Illuminate\Support\ServiceProvider;
use WhichBrowser\Parser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        AuthService::class,
        MfaTokenResolver::class,
    ];

    public function register(): void
    {
        // register telescope service provider for non-prod only
        if (! $this->app->environment(EnvEnum::PRODUCTION->value)) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->singleton(Parser::class, function (): Parser {
            return new Parser(getallheaders());
        });

        foreach ($this->services as $service) {
            $this->app->singleton($service);
        }
    }

    public function boot(): void
    {
        //
    }
}
