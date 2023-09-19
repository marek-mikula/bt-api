<?php

namespace Apis\WhaleAlert\Http\Client\Concerns;

use Apis\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Carbon\Carbon;
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
