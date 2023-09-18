<?php

namespace Domain\WhaleAlert\Http\Client;

use Carbon\Carbon;
use Domain\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Domain\WhaleAlert\Http\Client\Concerns\WhaleAlertClientInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WhaleAlertClient implements WhaleAlertClientInterface
{
    public function status(): Response
    {
        $response = $this->request()
            ->get('/v1/status');

        if (! $response->successful()) {
            throw new WhaleAlertRequestException($response);
        }

        return $response;
    }

    public function transactions(Carbon $from, Carbon $to = null, int $min = null, string $currency = null): Response
    {
        $params = [
            // starting timestamp
            'start' => $from->getTimestamp(),

            // max. number of items in response, max. is 100
            'limit' => 100,
        ];

        // ending timestamp
        if ($to !== null) {
            $params['end'] = $to->getTimestamp();
        }

        // min. value of transaction in $, min. is $500,000
        if ($min !== null) {
            $params['min_value'] = $min;
        }

        // only specific currency
        if (! empty($currency)) {
            $params['currency'] = $currency;
        }

        $response = $this->request()
            ->get('/v1/transactions', $params);

        if (! $response->successful()) {
            throw new WhaleAlertRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) config('whale-alert.url'))
            ->withHeaders([
                'X-WA-API-KEY' => (string) config('whale-alert.key'),
            ])
            ->contentType('application/json');
    }
}
