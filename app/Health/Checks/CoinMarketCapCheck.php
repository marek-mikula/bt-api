<?php

namespace App\Health\Checks;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class CoinmarketcapCheck extends Check
{
    protected ?string $name = 'Coinmarketcap.com';

    public function __construct(
        private readonly CoinmarketcapClientInterface $client,
    ) {
        parent::__construct();
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $this->client->keyInfo();
        } catch (CoinmarketcapRequestException $e) {
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
