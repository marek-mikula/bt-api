<?php

namespace Domain\WhaleAlert\Http\Client\Concerns;

use Carbon\Carbon;
use Domain\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Illuminate\Http\Client\Response;

interface WhaleAlertClientInterface
{
    /**
     * @throws WhaleAlertRequestException
     */
    public function status(): Response;

    /**
     * @throws WhaleAlertRequestException
     */
    public function transactions(Carbon $from, Carbon $to = null, int $min = null, string $currency = null): Response;
}
