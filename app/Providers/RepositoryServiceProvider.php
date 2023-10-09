<?php

namespace App\Providers;

use App\Repositories\Alert\AlertRepository;
use App\Repositories\Alert\AlertRepositoryInterface;
use App\Repositories\Asset\AssetRepository;
use App\Repositories\Asset\AssetRepositoryInterface;
use App\Repositories\Currency\CurrencyRepository;
use App\Repositories\Currency\CurrencyRepositoryInterface;
use App\Repositories\Limits\LimitsRepository;
use App\Repositories\Limits\LimitsRepositoryInterface;
use App\Repositories\MfaToken\MfaTokenRepository;
use App\Repositories\MfaToken\MfaTokenRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\QuizResult\QuizResultRepository;
use App\Repositories\QuizResult\QuizResultRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\WhaleAlert\WhaleAlertRepository;
use App\Repositories\WhaleAlert\WhaleAlertRepositoryInterface;
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
        AlertRepositoryInterface::class => AlertRepository::class,
        LimitsRepositoryInterface::class => LimitsRepository::class,
        AssetRepositoryInterface::class => AssetRepository::class,
        WhaleAlertRepositoryInterface::class => WhaleAlertRepository::class,
        CurrencyRepositoryInterface::class => CurrencyRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositories as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }
    }

    public function provides(): array
    {
        return array_keys($this->repositories);
    }
}
