<?php

namespace Domain\WhaleAlert\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class WhaleAlertRequestException extends Exception
{
    public function __construct(
        public readonly Response $response,
    ) {
        parent::__construct('Request to WhaleAlert failed.');
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
