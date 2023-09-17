<?php

namespace Domain\Binance\Exceptions;

use Domain\Binance\Data\BanCacheData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Exception;

class BinanceBanException extends Exception
{
    public function __construct(
        public readonly BinanceEndpointEnum $endpoint,
        public readonly BanCacheData $ban,
    ) {
        parent::__construct(message: vsprintf('Binance banned requests to EP %s. Please wait %s ms.', [
            $this->endpoint->value,
            $this->ban->waitMs,
        ]));
    }

    public function context(): array
    {
        return [
            'endpoint' => $this->endpoint->value,
            'waitMs' => $this->ban->waitMs,
        ];
    }
}
