<?php

namespace Domain\User\Jobs;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceBanException;
use Apis\Binance\Exceptions\BinanceLimitException;
use Apis\Binance\Http\BinanceApi;
use App\Enums\QueueEnum;
use App\Jobs\BaseJob;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\User;
use Domain\User\Notifications\AssetsSyncedNotification;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Support\Collection;

class SyncAssetsJob extends BaseJob
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
    ) {
        $this->onQueue(QueueEnum::ASSETS->value);
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

        // retrieve supported currencies for
        // given assets

        /** @var Collection<Currency> $currencies */
        $currencies = Currency::query()
            ->whereIn('symbol', $assets->pluck('asset')->all())
            ->get();

        $processedIds = collect();

        foreach ($assets as $asset) {
            /** @var Currency|null $currency */
            $currency = $currencies->first(function (Currency $currency) use ($asset): bool {
                return $asset['asset'] === $currency->symbol;
            });

            if ($currency) {
                // supported currency

                /** @var Asset $model */
                $model = $this->user->assets()->updateOrCreate([
                    'currency_id' => $currency->id,
                ], [
                    'balance' => floatval($asset['free']),
                    'currency_symbol' => null,
                ]);
            } else {
                // not supported currency,
                // but we still save it
                // for informational purpose

                /** @var Asset $model */
                $model = $this->user->assets()->updateOrCreate([
                    'currency_symbol' => (string) $asset['asset'],
                ], [
                    'balance' => floatval($asset['free']),
                    'currency_id' => null,
                ]);
            }

            $processedIds->push($model->id);
        }

        // delete old balances

        $this->user->assets()
            ->whereNotIn('id', $processedIds->all())
            ->delete();

        // update timestamp

        $this->user->touch('assets_synced_at');

        // send notification

        $this->user->notify(new AssetsSyncedNotification());
    }
}
