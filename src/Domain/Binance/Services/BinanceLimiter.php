<?php

namespace Domain\Binance\Services;

use Domain\Binance\Data\BanCacheData;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Data\LimitCacheData;
use Domain\Binance\Data\LimitData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Domain\Binance\Enums\BinanceErrorEnum;
use Domain\Binance\Enums\BinanceLimitTypeEnum;
use Domain\Binance\Exceptions\BinanceBanException;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class BinanceLimiter
{
    private readonly int $timestampMs;

    public function __construct(
        private readonly Repository $config,
    ) {
        // save the timestamp in ms for further process
        $this->timestampMs = now()->getTimestampMs();
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     * @throws BinanceBanException
     */
    public function limit(int $weight, BinanceEndpointEnum $endpoint, callable $request, ?KeyPairData $keyPair, ...$args): BinanceResponse
    {
        // if keyPair is set, push it to the beginning
        // of the arguments array
        if (! empty($keyPair)) {
            $args = [$keyPair, ...$args];
        }

        // check if limiter is enabled, if so
        // return response immediately

        if (! $this->config->get('binance.limiter')) {
            /** @var BinanceResponse $response */
            $response = $request(...$args);

            return $response;
        }

        // firstly, check if we haven't got banned
        // in the past, so we don't spam the API
        // more, which would result in longer ban :/

        $this->checkBan($endpoint, $keyPair);

        $processData = [];

        // before the request, check if we would
        // exceed the given limits, so we don't
        // span Binance API and get banned

        foreach ($endpoint->getLimits() as $limit) {
            // obtain cache key
            $key = $this->getLimitCacheKey($endpoint, $limit, $keyPair);

            // save data to array for further process
            $processData[$key] = [$limit, $this->checkLimit($weight, $key, $endpoint, $limit)];
        }

        try {
            /** @var BinanceResponse $response */
            $response = $request(...$args);
        } catch (BinanceRequestException $e) {
            // interfere TOO_MANY_REQUESTS response
            // and save ban if any, so we don't
            // spam API

            // we probably got banned?
            // maybe the limiter on our side
            // broke down => handle the ban
            // response, block EP calling and
            // throw the ban exception

            if ($e->response->isError(BinanceErrorEnum::TOO_MANY_REQUESTS)) {
                $this->storeBan($e->response, $endpoint, $keyPair);
            }

            throw $e; // rethrow the exception so it can propagate to our handler
        }

        foreach ($processData as $key => [$limit, $data]) {
            $this->increment($key, $weight, $limit, $data);
        }

        return $response;
    }

    private function increment(string $key, int $weight, LimitData $limit, ?LimitCacheData $data): void
    {
        // create cache object if not created yet
        $data = $data === null ? LimitCacheData::from(['timestampMs' => $this->timestampMs]) : $data;

        // increment the weight
        $data->weightUsed += $weight;

        // count for how much ms should be the cache stored
        $cacheTimeInMs = ($data->timestampMs + $limit->getPeriodInMs()) - $this->timestampMs;

        // save object to cache
        Cache::tags(['binance', 'binance-limiter'])->put($key, $data, now()->addMilliseconds($cacheTimeInMs));
    }

    /**
     * @throws BinanceLimitException
     */
    private function checkLimit(int $weight, string $key, BinanceEndpointEnum $endpoint, LimitData $limit): ?LimitCacheData
    {
        /** @var LimitCacheData|null $data */
        $data = Cache::tags(['binance', 'binance-limiter'])->get($key);

        if ($data === null) {
            return null;
        }

        // we probably hit the breaking point
        // where cache should be deleted, but it wasn't,
        // the limit is no longer applicable
        if (($this->timestampMs - $data->timestampMs) >= $limit->getPeriodInMs()) {
            return null;
        }

        // we would exceed the max number of weight if we would
        // call the endpoint one more time
        // => throw exception
        if (($data->weightUsed + $weight) > $limit->value) {
            throw new BinanceLimitException(
                endpoint: $endpoint,
                limit: $limit,
                waitMs: ($data->timestampMs + $limit->getPeriodInMs()) - $this->timestampMs,
                weight: $weight,
                weightUsed: $data->weightUsed,
            );
        }

        return $data;
    }

    /**
     * @throws BinanceBanException
     */
    private function storeBan(BinanceResponse $response, BinanceEndpointEnum $endpoint, ?KeyPairData $keyPair): void
    {
        // 418 = IP ban
        $cacheKey = $response->status() === 418 ? $this->getIpBanCacheKey($endpoint) : $this->getBanCacheKey($endpoint, $keyPair);

        $waitSeconds = (int) ($response->header('Retry-After') ?? 1); // 1s as default, missing header?

        $value = new BanCacheData($this->timestampMs, $waitSeconds * 1000);

        Cache::tags(['binance', 'binance-limiter'])->put($cacheKey, $value, now()->addSeconds($waitSeconds));

        throw new BinanceBanException($endpoint, $value);
    }

    /**
     * @throws BinanceBanException
     */
    private function checkBan(BinanceEndpointEnum $endpoint, ?KeyPairData $keyPair): void
    {
        /** @var BanCacheData|null $ipBan */
        $ipBan = Cache::tags(['binance', 'binance-limiter'])->get($this->getIpBanCacheKey($endpoint));

        if ($ipBan !== null) {
            throw new BinanceBanException($endpoint, $ipBan);
        }

        /** @var BanCacheData|null $ban */
        $ban = Cache::tags(['binance', 'binance-limiter'])->get($this->getBanCacheKey($endpoint, $keyPair));

        if ($ban !== null) {
            throw new BinanceBanException($endpoint, $ban);
        }
    }

    private function getIpBanCacheKey(BinanceEndpointEnum $endpoint): string
    {
        return vsprintf('binance-ban:%s', [
            $endpoint->value,
        ]);
    }

    private function getBanCacheKey(BinanceEndpointEnum $endpoint, ?KeyPairData $keyPair): string
    {
        $id = $keyPair === null ? 'ip' : md5("{$keyPair->publicKey}-{$keyPair->secretKey}");

        return vsprintf('binance-ban:%s-%s', [
            $id,
            $endpoint->value,
        ]);
    }

    private function getLimitCacheKey(BinanceEndpointEnum $endpoint, LimitData $limit, ?KeyPairData $keyPair): string
    {
        // UID limits are tied to users profile, so we need keyPair to
        // uniquely identify the user calling the API
        if ($limit->type === BinanceLimitTypeEnum::UID && empty($keyPair)) {
            throw new InvalidArgumentException('$keyPair argument is needed for UID endpoints.');
        }

        $id = $limit->type === BinanceLimitTypeEnum::IP ? 'ip' : md5("{$keyPair->publicKey}-{$keyPair->secretKey}");

        // ! shared limits are not endpoint specific
        // => do not include endpoint name in the
        //    cache key string

        if ($limit->shared) {
            return vsprintf('binance-limit:%s-%s-%s', [
                $id,
                $limit->per,
                $limit->period->value,
            ]);
        }

        return vsprintf('binance-limit:%s-%s-%s-%s', [
            $id,
            $endpoint->value,
            $limit->per,
            $limit->period->value,
        ]);
    }
}
