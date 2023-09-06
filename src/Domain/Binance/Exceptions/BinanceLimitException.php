<?php

namespace Domain\Binance\Exceptions;

use Domain\Binance\Data\LimitData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Exception;

class BinanceLimitException extends Exception
{
    public function __construct(
        public readonly BinanceEndpointEnum $endpoint,
        public readonly LimitData $limit,
        public readonly int $waitMs,
    ) {
        parent::__construct(message: vsprintf('Binance limit %s/%s%s for EP %s (w: %s) exceeded. Please wait %s ms.', [
            $this->limit->value,
            $this->limit->per,
            $this->limit->period->value,
            $this->endpoint->value,
            $this->endpoint->getWeight(),
            $this->waitMs,
        ]));
    }

    public function context(): array
    {
        return [
            'endpoint' => $this->endpoint->value,
            'limit' => $this->limit->toArray(),
        ];
    }
}
