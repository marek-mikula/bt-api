<?php

namespace App\Providers;

use App\Repositories\MfaToken\MfaTokenRepository;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\QuizResult\QuizResultRepository;
use App\Repositories\QuizResult\QuizResultRepositoryInterface;
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
        MfaTokenRepositoryInterface::class => MfaTokenRepository::class,
        QuizResultRepositoryInterface::class => QuizResultRepository::class,
        NotificationRepositoryInterface::class => NotificationRepository::class,
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
