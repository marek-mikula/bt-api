<?php

namespace Apis\Coinmarketcap\Checks;

use Apis\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class CoinmarketcapCheck extends Check
{
    protected ?string $name = 'Coinmarketcap.com';

    public function __construct(
        private readonly CoinmarketcapApi $api,
    ) {
        parent::__construct();
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $this->api->keyInfo();
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
