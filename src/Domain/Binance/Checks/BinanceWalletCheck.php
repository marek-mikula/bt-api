<?php

namespace Domain\Binance\Checks;

use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceApi;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class BinanceWalletCheck extends Check
{
    protected ?string $name = 'Binance.com - Wallet';

    public function __construct(
        private readonly BinanceApi $api,
    ) {
        parent::__construct();
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $response = $this->api->wallet->systemStatus();
        } catch (BinanceRequestException $e) {
            return $result
                ->failed('Down')
                ->meta([
                    'status' => $e->response->status(),
                    'body' => $e->response->json(),
                ]);
        }

        $msg = $response->json('msg', 'normal'); // normal, system_maintenance

        if ($msg === 'system_maintenance') {
            return $result->warning('Maintenance');
        }

        return $result->ok('Running');
    }
}
