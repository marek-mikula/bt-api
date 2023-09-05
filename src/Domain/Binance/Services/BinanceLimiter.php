<?php

namespace Domain\Binance\Services;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Data\LimitCacheData;
use Domain\Binance\Data\LimitData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Domain\Binance\Enums\BinanceLimitTypeEnum;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Exceptions\BinanceRequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class BinanceLimiter
{
    private readonly int $timestampMs;

    public function __construct()
    {
        // save the timestamp in ms for further process
        $this->timestampMs = now()->getTimestampMs();
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function limit(BinanceEndpointEnum $endpoint, callable $request, ?KeyPairData $keyPair, ...$args): Response
    {
        $processData = [];

        // before the request, check if we would
        // exceed the given limits, so we don't
        // span Binance API and get banned

        foreach ($endpoint->getLimits() as $limit) {
            // obtain cache key
            $key = $this->getCacheKey($endpoint, $limit, $keyPair);

            // save data to array for further process
            $processData[$key] = [$limit, $this->check($key, $endpoint, $limit)];
        }

        // if keyPair is set, push it to the beginning
        // of the arguments array
        if (! empty($keyPair)) {
            $args = [$keyPair, ...$args];
        }

        /** @var Response $response */
        $response = $request(...$args);

        foreach ($processData as $key => [$limit, $data]) {
            $this->increment($key, $limit, $data);
        }

        return $response;
    }

    private function increment(string $key, LimitData $limit, ?LimitCacheData $data): void
    {
        // create cache object if not created yet
        $data = $data === null ? LimitCacheData::from(['timestampMs' => $this->timestampMs]) : $data;

        // increment the value
        $data->tries += 1;

        // count for how much ms should be the cache stored
        $cacheTimeInMs = ($data->timestampMs + $limit->getPeriodInMs()) - $this->timestampMs;

        // save object to cache
        Cache::put($key, $data, now()->addMilliseconds($cacheTimeInMs));
    }

    /**
     * @throws BinanceLimitException
     */
    private function check(string $key, BinanceEndpointEnum $endpoint, LimitData $limit): ?LimitCacheData
    {
        /** @var LimitCacheData|null $data */
        $data = Cache::get($key);

        if ($data === null) {
            return null;
        }

        // we probably hit the breaking point
        // where cache should be deleted, but it wasn't,
        // the limit is no longer applicable
        if (($this->timestampMs - $data->timestampMs) >= $limit->getPeriodInMs()) {
            return null;
        }

        // we would exceed the max number of tries if we would
        // call the endpoint one more time
        // => throw exception
        if ((($data->tries + 1) * $endpoint->getWeight()) > $limit->value) {
            throw new BinanceLimitException(
                endpoint: $endpoint,
                limit: $limit,
                waitMs: ($data->timestampMs + $limit->getPeriodInMs()) - $this->timestampMs,
            );
        }

        return $data;
    }

    private function getCacheKey(BinanceEndpointEnum $endpoint, LimitData $limit, ?KeyPairData $keyPair): string
    {
        // UID limits are tied to users profile, so we need keyPair to
        // uniquely identify the user calling the API
        if ($limit->type === BinanceLimitTypeEnum::UID && empty($keyPair)) {
            throw new InvalidArgumentException('$keyPair argument is needed for UID endpoints.');
        }

        $id = $limit->type === BinanceLimitTypeEnum::IP ? 'ip' : md5("{$keyPair->publicKey}-{$keyPair->secretKey}");

        return "{$id}-{$endpoint->value}-{$limit->per}-{$limit->period->value}";
    }
}
