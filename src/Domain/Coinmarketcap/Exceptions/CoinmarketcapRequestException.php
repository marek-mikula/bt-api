<?php

namespace Domain\Coinmarketcap\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class CoinmarketcapRequestException extends Exception
{
    public function __construct(
        public readonly Response $response,
    ) {
        parent::__construct('Request to Coinmarketcap failed.');
    }

    public function context(): array
    {
        return [
            'status' => $this->response->status(),
            'body' => $this->response->json(),
        ];
    }
}
