<?php

namespace Apis\Coinmarketcap\Exceptions;

use Exception;

class CoinmarketcapLimitException extends Exception
{
    public function __construct(
        private readonly int $requestsPerMinute
    ) {
        parent::__construct(vsprintf('CoinMarketCap limit of %s requests per minute exceeded.', [
            $this->requestsPerMinute,
        ]));
    }

    public function context(): array
    {
        return [
            'requestsPerMinute' => $this->requestsPerMinute,
        ];
    }
}
