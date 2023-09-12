<?php

namespace Domain\User\Jobs;

use App\Enums\QueueEnum;
use App\Jobs\BaseJob;
use App\Models\Currency;
use App\Models\User;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceBanException;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Http\BinanceApi;
use Domain\User\Notifications\AssetsSyncedNotification;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Collection;

class SyncAssetsJob extends BaseJob
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
    ) {
        $this->onQueue(QueueEnum::ASSETS->value);
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->user->id, 5),
        ];
    }

    public function handle(BinanceApi $binanceApi): void
    {
        try {
            $response = $binanceApi->spot->account(KeyPairData::fromUser($this->user));
        } catch (BinanceBanException $e) {
            $this->release(now()->addMilliseconds($e->ban->waitMs));

            return;
        } catch (BinanceLimitException $e) {
            $this->release(now()->addMilliseconds($e->waitMs));

            return;
        }

        // filter only positive balances

        $assets = $response->collect('balances')
            ->filter(function (array $item): bool {
                return floatval($item['free']) > 0;
            });

        /** @var Collection<Currency> $currencies */
        $currencies = Currency::query()
            ->whereIn('symbol', $assets->pluck('asset')->all())
            ->get();

        foreach ($currencies as $currency) {
            /** @var array $asset */
            $asset = $assets->first(function (array $item) use ($currency): bool {
                return $item['asset'] === $currency->symbol;
            });

            $this->user->assets()->updateOrCreate([
                'currency_id' => $currency->id,
            ], [
                'balance' => floatval($asset['free']),
            ]);
        }

        // delete old balances

        $this->user->assets()
            ->whereNotIn('currency_id', $currencies->pluck('id')->all())
            ->delete();

        // update timestamp

        $this->user->touch('assets_synced_at');

        // send notification

        $this->user->notify(new AssetsSyncedNotification());
    }
}
