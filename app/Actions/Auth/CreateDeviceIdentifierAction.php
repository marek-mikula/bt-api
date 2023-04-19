<?php

namespace App\Actions\Auth;

use Lorisleiva\Actions\Concerns\AsAction;
use WhichBrowser\Parser;

class CreateDeviceIdentifierAction
{
    use AsAction;

    public function __construct(
        private readonly Parser $parser
    ) {
    }

    public static function create(): string
    {
        return self::run();
    }

    private function handle(): string
    {
        return $this->parser->toString();
    }
}
