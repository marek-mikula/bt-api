<?php

namespace Domain\User\Console\Commands;

use Domain\User\Services\AssetSyncService;
use Illuminate\Console\Command;

class SyncAssetsCommand extends Command
{
    protected $signature = 'assets:sync';

    protected $description = 'Pushes jobs, which synchronize user\'s assets with Binance, into queue.';

    public function handle(AssetSyncService $service): int
    {
        $service->sync();

        return 0;
    }
}
