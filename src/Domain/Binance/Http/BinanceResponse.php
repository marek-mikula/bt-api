<?php

namespace Domain\Binance\Http;

use Domain\Binance\Enums\BinanceErrorEnum;
use Illuminate\Http\Client\Response;

/**
 * @mixin Response
 */
class BinanceResponse
{
    public function __construct(
        private readonly Response $response,
    ) {
    }

    public function isError(BinanceErrorEnum $error): bool
    {
        return $this->response->json('code') === $error->value;
    }

    public function __get(string $name)
    {
        return $this->response->{$name};
    }

    public function __call(string $name, array $arguments)
    {
        return $this->response->{$name}(...$arguments);
    }
}
