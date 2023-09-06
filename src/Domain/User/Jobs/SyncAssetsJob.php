<?php

namespace Domain\User\Jobs;

use App\Jobs\BaseJob;
use App\Models\User;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceBanException;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Http\BinanceApi;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncAssetsJob extends BaseJob
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
    ) {
        //
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->user->id))->releaseAfter(5)];
    }

    public function handle(BinanceApi $binanceApi): void
    {
        try {
            $response = $binanceApi->wallet->assets(KeyPairData::fromUser($this->user));
        } catch (BinanceBanException $e) {
            $this->release(now()->addMilliseconds($e->ban->waitMs));

            return;
        } catch (BinanceLimitException $e) {
            $this->release(now()->addMilliseconds($e->waitMs));

            return;
        }

        $assets = $response->collect();

        $tickers = $assets->pluck('asset');

        // delete old balances
        $this->user->assets()
            ->whereNotIn('currency', $tickers->all())
            ->delete();

        foreach ($assets as $asset) {
            $this->user->assets()->updateOrCreate([
                'currency' => (string) $asset['asset'],
            ], [
                'value' => floatval($asset['free']),
            ]);
        }
    }
}
