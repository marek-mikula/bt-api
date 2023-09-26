<?php

namespace Domain\Dashboard\Data;

use App\Data\BaseData;

class DashboardMarketMetrics extends BaseData
{
    public function __construct(
        public readonly float $ethDominance,
        public readonly float $ethDominanceYesterday,
        public readonly float $ethDominancePercentageChange,
        public readonly float $btcDominance,
        public readonly float $btcDominanceYesterday,
        public readonly float $btcDominancePercentageChange,
        public readonly float $totalMarketCap,
        public readonly float $totalMarketCapYesterday,
        public readonly float $totalMarketCapPercentageChange,
        public readonly string $totalMarketCapCurrency,
    ) {
    }
}
