<?php

namespace App\Health\Checks;

use Domain\CoinMarketCap\Exceptions\CoinMarketCapRequestException;
use Domain\CoinMarketCap\Http\Concerns\CoinMarketCapClientInterface;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class CoinMarketCapCheck extends Check
{
    protected ?string $name = 'Coinmarketcap.com';

    public function __construct(
        private readonly CoinMarketCapClientInterface $client,
    ) {
        parent::__construct();
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $this->client->keyInfo();
        } catch (CoinMarketCapRequestException $e) {
            return $result
                ->failed('Down')
                ->meta([
                    'status' => $e->response->status(),
                    'body' => $e->response->json(),
                ]);
        }

        return $result->ok('Running');
    }
}
