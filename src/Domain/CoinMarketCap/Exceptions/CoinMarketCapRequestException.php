<?php

namespace Domain\CoinMarketCap\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class CoinMarketCapRequestException extends Exception
{
    public function __construct(
        public readonly Response $response,
    ) {
        parent::__construct("Request to Coinmarketcap failed.");
    }

    public function context(): array
    {
        return [
            'status' => $this->response->status(),
            'body' => $this->response->body(),
        ];
    }
}