<?php

namespace Domain\WhaleAlert\Providers;

use Domain\WhaleAlert\Http\Client\Concerns\WhaleAlertClientInterface;
use Domain\WhaleAlert\Http\Client\WhaleAlertClient;
use Domain\WhaleAlert\Http\Client\WhaleAlertClientMock;
use Domain\WhaleAlert\Http\WhaleAlertApi;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class WhaleAlertDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        WhaleAlertApi::class,
    ];

    public function register(): void
    {
        $this->app->singleton(WhaleAlertClientInterface::class, static function () {
            return config('whale-alert.mock')
                ? app(WhaleAlertClientMock::class)
                : app(WhaleAlertClient::class);
        });

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
            [
                WhaleAlertClientInterface::class,
            ],
            $this->services
        );
    }
}
