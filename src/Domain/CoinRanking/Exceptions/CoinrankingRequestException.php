<?php

namespace Domain\CoinRanking\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class CoinrankingRequestException extends Exception
{
    public function __construct(
        public readonly Response $response,
    ) {
        parent::__construct('Request to Coinranking failed.');
    }

    public function context(): array
    {
        return [
            'status' => $this->response->status(),
            'body' => $this->response->body(),
        ];
    }
}
