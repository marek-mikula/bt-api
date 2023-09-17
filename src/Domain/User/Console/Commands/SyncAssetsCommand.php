<?php

namespace Domain\User\Console\Commands;

use App\Models\User;
use Domain\User\Jobs\SyncAssetsJob;
use Illuminate\Console\Command;

class SyncAssetsCommand extends Command
{
    protected $signature = 'user:sync-assets {user}';

    protected $description = 'Synchronizes user\'s assets.';

    public function handle(): int
    {
        $userId = (int) $this->argument('user');

        /** @var User|null $user */
        $user = User::query()->find($userId);

        if (! $user) {
            $this->error("User with ID {$userId} does not exist.");

            return 1;
        }

        SyncAssetsJob::dispatchSync($user);

        $this->info('Assets synchronized!');

        return 0;
    }
}
