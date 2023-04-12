<?php

namespace App\Providers;

use App\Repositories\MfaToken\MfaTokenRepository;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\RefreshToken\RefreshTokenRepository;
use App\Repositories\RefreshToken\RefreshTokenRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array<class-string, class-string>
     */
    private array $repositories = [
        UserRepositoryInterface::class => UserRepository::class,
        RefreshTokenRepositoryInterface::class => RefreshTokenRepository::class,
        MfaTokenRepositoryInterface::class => MfaTokenRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositories as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }
    }

    public function boot(): void
    {
        //
    }

    public function provides(): array
    {
        return array_keys($this->repositories);
    }
}
