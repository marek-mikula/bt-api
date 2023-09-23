<?php

namespace Domain\Currency\Console\Commands;

use Domain\Currency\Schedules\SyncCurrenciesSchedule;
use Illuminate\Console\Command;

class SyncCurrenciesCommand extends Command
{
    protected $signature = 'currency:sync';

    protected $description = 'Synchronizes currencies with Binance and Coinmarketcap.';

    public function handle(): int
    {
        SyncCurrenciesSchedule::call();

        $this->info('Currencies synchronized!');

        return 0;
    }
}
