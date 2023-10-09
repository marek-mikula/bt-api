<?php

namespace Domain\Currency\Services;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Http\BinanceApi;
use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use App\Models\Currency;
use App\Models\CurrencyPair;
use Domain\Currency\Data\BinanceCurrencyData;
use Domain\Currency\Data\BinancePairData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CurrencyIndexer
{
    public function __construct(
        private readonly CoinmarketcapApi $coinmarketcapApi,
        private readonly BinanceApi $binanceApi,
    ) {
    }

    /**
     * Pulls data from multiple APIs and saves it to DB
     * for further use - ID mapping, info, etc.
     *
     * Everything is based on data from Binance, because
     * the whole app operates above Binance API, so we ignore
     * other tokens and cryptocurrencies that are not supported
     * by Binance.
     */
    public function index(): void
    {
        // this collection will hold all the
        // updated/inserted IDs of currency
        // models for further use

        $ids = collect();

        // firstly, pull all the supported
        // currencies from Binance including
        // fiat and crypto together and
        // transform it to data objects

        /** @var Collection<BinanceCurrencyData> $currencies */
        $currencies = $this->binanceApi->wallet->allCoins(KeyPairData::admin())
            ->collect()
            ->map(static fn (array $item): BinanceCurrencyData => BinanceCurrencyData::from([
                'symbol' => (string) $item['coin'],
                'name' => (string) $item['name'],
                'isFiat' => (bool) $item['isLegalMoney'],
            ]));

        // process fiats

        $fiats = $currencies->filter(function (BinanceCurrencyData $item): bool {
            return $item->isFiat;
        });

        $ids = $ids->merge($this->indexFiat($fiats));

        // process cryptos

        $cryptos = $currencies->filter(function (BinanceCurrencyData $item): bool {
            return ! $item->isFiat;
        });

        $ids = $ids->merge($this->indexCrypto($cryptos));

        // remove not-updated/not-created models
        // from index

        Currency::query()
            ->whereNotIn('id', $ids->all())
            ->delete();

        // now index all current trading pairs

        $this->indexPairs();
    }

    /**
     * @param  Collection<BinanceCurrencyData>  $fiats
     * @return Collection<int> inserted or updated IDs
     * of currency models
     */
    private function indexFiat(Collection $fiats): Collection
    {
        $result = collect();

        // extract only symbols from fiat collection

        $symbols = $fiats->pluck('symbol');

        // use very big perPage parameter,
        // there won't be any more than 5000
        // currencies, so we are safe, that we
        // are getting all of them

        $map = $this->coinmarketcapApi->mapFiat(perPage: 5000)
            ->collect('data')
            ->filter(static fn (array $item): bool => $symbols->contains($item['symbol']));

        $duplicates = $map->pluck('symbol')->duplicates();

        foreach ($fiats as $fiat) {
            $isDuplicated = $duplicates->contains($fiat->symbol);

            $meta = null;

            // if the currently processed fiat currency
            // is duplicated in the map, use name and
            // symbol to find the right value, otherwise
            // use only symbol

            if ($isDuplicated) {
                $meta = $map->first(static function (array $item) use ($fiat): bool {
                    return $item['symbol'] === $fiat->symbol && $item['name'] === $fiat->name;
                });
            }

            if (! $meta) {
                $meta = $map->first(static function (array $item) use ($fiat): bool {
                    return $item['symbol'] === $fiat->symbol;
                });
            }

            // fiat does not exist in Coinmarketcap
            // => skip, we do not support this!
            if (! $meta) {
                continue;
            }

            /** @var Currency $model */
            $model = Currency::query()->updateOrCreate([
                'symbol' => Str::upper($fiat->symbol),
            ], [
                'name' => (string) $meta['name'],
                'is_fiat' => 1,
                'cmc_id' => (int) $meta['id'],
                'meta' => Arr::only($meta, [
                    'sign',
                ]),
            ]);

            $result->push($model->id);
        }

        return $result;
    }

    /**
     * @param  Collection<BinanceCurrencyData>  $cryptos
     * @return Collection<int> inserted or updated IDs
     * of currency models
     */
    private function indexCrypto(Collection $cryptos): Collection
    {
        $result = collect();

        // extract collection of symbols from Binance,
        // so we can map it to Coinmarketcap IDs

        $symbols = $cryptos->pluck('symbol');

        // Retrieve mappings of given symbols

        /** @var Collection<array> $mappings */
        $mappings = $this->coinmarketcapApi->map(symbols: $symbols)
            ->collect('data')
            ->keyBy('id');

        // Retrieve IDs of given symbols

        $ids = $mappings->pluck('id')->all();

        // Retrieve metadata for each given ID

        $metadata = $this->coinmarketcapApi->coinMetadata(id: $ids)
            ->collect('data');

        $duplicates = $metadata->pluck('symbol')->duplicates();

        foreach ($cryptos as $crypto) {
            $isDuplicated = $duplicates->contains($crypto->symbol);

            $meta = null;

            // if the currently processed cryptocurrency
            // is duplicated in the map, use name and
            // symbol to find the right value, otherwise
            // use only symbol

            if ($isDuplicated) {
                $meta = $metadata->first(static function (array $item) use ($crypto): bool {
                    return $item['symbol'] === $crypto->symbol && $item['name'] === $crypto->name;
                });
            }

            if (! $meta) {
                $meta = $metadata->first(static function (array $item) use ($crypto): bool {
                    return $item['symbol'] === $crypto->symbol;
                });
            }

            // cryptocurrency does not exist in Coinmarketcap
            // => skip, we do not support this!
            if (! $meta) {
                continue;
            }

            /** @var array $mapping */
            $mapping = $mappings->get((int) $meta['id']);

            /** @var Currency $model */
            $model = Currency::query()->updateOrCreate([
                'symbol' => Str::upper($crypto->symbol),
            ], [
                'name' => $crypto->name,
                'is_fiat' => 0,
                'cmc_id' => (int) $meta['id'],
                'cmc_rank' => empty($mapping['rank']) ? 99_999 : (int) $mapping['rank'],
                'meta' => Arr::except($meta, [
                    'id',
                    'name',
                    'symbol',
                    'tag-names',
                    'tag-groups',
                    'self_reported_tags',
                ]),
            ]);

            $result->push($model->id);
        }

        return $result;
    }

    private function indexPairs(): void
    {
        // this collection will hold all the
        // updated/inserted IDs of currency pair
        // models for further use

        $ids = collect();

        // this collection will hold all the
        // updated/inserted IDs of base currency
        // models for further use

        $baseCurrencyIds = collect();

        // pull all trading symbols from Binance API
        // and group them by base asset

        /** @var Collection<Collection<BinancePairData>> $symbolGroups */
        $symbolGroups = $this->binanceApi->marketData->exchangeInfo()
            ->collect('symbols')
            ->map(static fn (array $item): BinancePairData => BinancePairData::from([
                'symbol' => (string) $item['symbol'],
                'baseAsset' => (string) $item['baseAsset'],
                'quoteAsset' => (string) $item['quoteAsset'],
            ]))
            ->groupBy('baseAsset');

        foreach ($symbolGroups as $baseAsset => $quoteAssets) {
            /** @var Currency|null $baseAsset */
            $baseAsset = Currency::query()
                ->crypto()
                ->ofSymbol($baseAsset)->first();

            // asset is probably not supported

            if (! $baseAsset) {
                continue;
            }

            /** @var Collection<Currency> $quoteAssets */
            $quoteAssets = Currency::query()
                ->crypto()
                ->ofSymbols($quoteAssets->pluck('quoteAsset'))
                ->get();

            // none of any quote asset is supported

            if ($quoteAssets->isEmpty()) {
                continue;
            }

            foreach ($quoteAssets as $quoteAsset) {
                /** @var CurrencyPair $model */
                $model = CurrencyPair::query()->updateOrCreate([
                    'base_currency_id' => $baseAsset->id,
                    'quote_currency_id' => $quoteAsset->id,
                ], [
                    'symbol' => $baseAsset->symbol.$quoteAsset->symbol,
                ]);

                $ids->push($model->id);
            }

            $baseCurrencyIds->push($baseAsset->id);
        }

        // delete not updated pairs from DB

        CurrencyPair::query()
            ->whereNotIn('id', $ids->all())
            ->delete();

        // delete currencies which don't have
        // any trading pairs

        Currency::query()
            ->crypto()
            ->whereNotIn('id', $baseCurrencyIds->all())
            ->delete();
    }
}
