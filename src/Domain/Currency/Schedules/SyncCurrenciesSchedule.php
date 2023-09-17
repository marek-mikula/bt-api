<?php

namespace Domain\Currency\Schedules;

use App\Schedules\BaseSchedule;
use Domain\Currency\Services\CurrencyIndexer;

class SyncCurrenciesSchedule extends BaseSchedule
{
    public function __invoke(CurrencyIndexer $indexer): void
    {
        $indexer->index();
    }
}
