<?php

namespace Domain\OpenExchangeRates\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class OpenExchangeRatesRequestException extends Exception
{
    public function __construct(
        public readonly Response $response,
    ) {
        parent::__construct('Request to OpenExchangeRates failed.');
    }

    public function context(): array
    {
        return [
            'status' => $this->response->status(),
            'body' => $this->response->json(),
        ];
    }
}
