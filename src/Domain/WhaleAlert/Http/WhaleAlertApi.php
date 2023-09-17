<?php

namespace Domain\WhaleAlert\Http;

use Carbon\Carbon;
use Domain\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Domain\WhaleAlert\Http\Client\Concerns\WhaleAlertClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class WhaleAlertApi
{
    public function __construct(
        private readonly WhaleAlertClientInterface $client,
    ) {
    }

    /**
     * Retrieves the status od the API
     *
     * @throws WhaleAlertRequestException
     */
    public function status(): Response
    {
        return $this->client->status();
    }

    /**
     * Retrieves the whale transactions
     *
     * @param Carbon $from from timestamp
     * @param Carbon|null $to to timestamp
     * @param int|null $min min transaction value
     * @param Collection<string>|null $currencies list of currencies to return
     *
     * @throws WhaleAlertRequestException
     */
    public function transactions(Carbon $from, ?Carbon $to = null, ?int $min = null, ?Collection $currencies = null): Response
    {
        if ($min < 500_000) {
            throw new InvalidArgumentException('Min. transaction value must be greater or equal than $500,000.');
        }

        if (! $from->isPast()) {
            throw new InvalidArgumentException('Timestamp from must be in the past.');
        }

        if ($to !== null && $from->gt($to)) {
            throw new InvalidArgumentException('Timestamp to must be greater than timestamp from.');
        }

        return $this->client->transactions(
            from: $from,
            to: $to,
            min: $min,
            currencies: $currencies
        );
    }
}
