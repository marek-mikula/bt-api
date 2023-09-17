<?php

namespace Domain\Dashboard\Cache;

use Domain\Dashboard\Data\DashboardMarketMetrics;
use Domain\Dashboard\Data\DashboardToken;
use Domain\Dashboard\Services\DashboardService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardCache
{
    /**
     * @return Collection<DashboardToken>
     */
    public function getTopCryptocurrencies(): Collection
    {
        return Cache::tags([
            'dashboard',
            'dashboard-top-crypto',
        ])->remember('dashboard:top-crypto', now()->endOfDay(), static function (): Collection {
            /** @var DashboardService $dashboardService */
            $dashboardService = app(DashboardService::class);

            return $dashboardService->getCryptocurrenciesByMarketCap(5);
        });
    }

    public function getMarketMetrics(): DashboardMarketMetrics
    {
        return Cache::tags([
            'dashboard',
            'dashboard-market-metrics',
        ])->remember('dashboard:market-metrics', now()->endOfDay(), static function (): DashboardMarketMetrics {
            /** @var DashboardService $dashboardService */
            $dashboardService = app(DashboardService::class);

            return $dashboardService->getLatestMarketMetrics();
        });
    }
}
