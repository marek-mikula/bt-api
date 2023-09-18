<?php

namespace Domain\WhaleAlert\Checks;

use Domain\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Domain\WhaleAlert\Http\WhaleAlertApi;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class WhaleAlertCheck extends Check
{
    protected ?string $name = 'Whale-alert.io';

    public function __construct(
        private readonly WhaleAlertApi $api,
    ) {
        parent::__construct();
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $this->api->status();
        } catch (WhaleAlertRequestException $e) {
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
