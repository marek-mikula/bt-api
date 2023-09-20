<?php

namespace Apis\Binance\Exceptions;

use Apis\Binance\Http\BinanceResponse;
use Exception;

class BinanceRequestException extends Exception
{
    public function __construct(
        public readonly BinanceResponse $response,
    ) {
        $code = $this->response->json('code');
        $msg = $this->response->json('msg');

        parent::__construct("Request to Binance failed with code: \"{$code}\" and message: \"{$msg}\".");
    }

    public function context(): array
    {
        return [
            'status' => $this->response->status(),
            'headers' => $this->response->headers(),
            'body' => $this->response->json(),
        ];
    }
}