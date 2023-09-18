<?php

namespace App\Providers;

use App\Enums\EnvEnum;
use Domain\Binance\Checks\BinanceWalletCheck;
use Domain\Coinmarketcap\Checks\CoinmarketcapCheck;
use Domain\WhaleAlert\Checks\WhaleAlertCheck;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthCheckServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $expectedDebugMode = ! $this->app->environment(EnvEnum::PRODUCTION->value);

        Health::checks([
            DebugModeCheck::new()->expectedToBe($expectedDebugMode),
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            CoinmarketcapCheck::new(),
            BinanceWalletCheck::new(),
            WhaleAlertCheck::new(),
        ]);
    }
}
