<?php

namespace Domain\WhaleAlert\Http\Client\Concerns;

use Carbon\Carbon;
use Domain\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

interface WhaleAlertClientInterface
{
    /**
     * @throws WhaleAlertRequestException
     */
    public function status(): Response;

    /**
     * @throws WhaleAlertRequestException
     */
    public function transactions(Carbon $from, ?Carbon $to = null, ?int $min = null, ?Collection $currencies = null): Response;
}
