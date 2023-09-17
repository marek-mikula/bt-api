<?php

namespace Domain\Coinmarketcap\Services;

use Domain\Coinmarketcap\Data\LimitCacheData;
use Domain\Coinmarketcap\Exceptions\CoinmarketcapLimitException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;

class CoinmarketcapLimiter
{
    private const REQUESTS_PER_M = 30;

    private readonly int $timestampMs;

    public function __construct(
        private readonly Repository $config,
    ) {
        // save the timestamp in ms for further process
        $this->timestampMs = now()->getTimestampMs();
    }

    /**
     * @throws CoinmarketcapLimitException
     */
    public function limit(callable $request, ...$args): Response
    {
        // check if limiter is enabled, if so
        // return response immediately

        if (! $this->config->get('coinmarketcap.limiter')) {
            /** @var Response $response */
            $response = $request(...$args);

            return $response;
        }

        // current coinmarketcap limit of requests is 30/1m

        $cacheKey = $this->getCacheKey();

        $data = $this->checkLimit($cacheKey);

        /** @var Response $response */
        $response = $request(...$args);

        $this->increment($cacheKey, $data);

        return $response;
    }

    private function increment(string $key, ?LimitCacheData $data): void
    {
        // create cache object if not created yet
        $data = $data === null ? LimitCacheData::from(['timestampMs' => $this->timestampMs]) : $data;

        // increment the tries
        $data->tries += 1;

        // 1 minute in ms
        $periodInMs = 60 * 1000;

        // count for how much ms should be the cache stored
        $cacheTimeInMs = ($data->timestampMs + $periodInMs) - $this->timestampMs;

        // save object to cache
        Cache::tags(['coinmarketcap', 'coinmarketcap-limit'])->put($key, $data, now()->addMilliseconds($cacheTimeInMs));
    }

    /**
     * @throws CoinmarketcapLimitException
     */
    private function checkLimit(string $key): ?LimitCacheData
    {
        /** @var LimitCacheData|null $data */
        $data = Cache::tags([
            'coinmarketcap',
            'coinmarketcap-limit',
        ])->get($key);

        if ($data === null) {
            return null;
        }

        // 1 minute in ms
        $periodInMs = 60 * 1000;

        // we probably hit the breaking point
        // where cache should be deleted, but it wasn't,
        // the limit is no longer applicable
        if (($this->timestampMs - $data->timestampMs) >= $periodInMs) {
            return $data;
        }

        // we would exceed the max number of requests if we would
        // call the endpoint one more time
        // => throw exception
        if (($data->tries + 1) > self::REQUESTS_PER_M) {
            throw new CoinmarketcapLimitException(requestsPerMinute: self::REQUESTS_PER_M);
        }

        return $data;
    }

    private function getCacheKey(): string
    {
        return 'coinmarketcap:limit';
    }
}
