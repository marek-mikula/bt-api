<?php

namespace Domain\Dashboard\Cache;

use Domain\Coinmarketcap\Data\MarketMetrics;
use Domain\Coinmarketcap\Data\Token;
use Domain\Coinmarketcap\Services\CoinmarketcapService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardCache
{
    /**
     * @return Collection<Token>
     */
    public function getTopCryptocurrencies(): Collection
    {
        return Cache::remember('dashboard:top-crypto', now()->endOfDay(), static function (): Collection {
            /** @var CoinmarketcapService $coinmarketcapService */
            $coinmarketcapService = app(CoinmarketcapService::class);

            return $coinmarketcapService->getCryptocurrenciesByMarketCap(5);
        });
    }

    public function getMarketMetrics(): MarketMetrics
    {
        return Cache::remember('dashboard:market-metrics', now()->endOfDay(), static function (): MarketMetrics {
            /** @var CoinmarketcapService $coinmarketcapService */
            $coinmarketcapService = app(CoinmarketcapService::class);

            return $coinmarketcapService->getLatestMarketMetrics();
        });
    }
}
