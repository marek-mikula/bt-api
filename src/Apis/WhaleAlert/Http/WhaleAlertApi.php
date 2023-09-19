<?php

namespace Apis\WhaleAlert\Http;

use Apis\WhaleAlert\Exceptions\WhaleAlertRequestException;
use Apis\WhaleAlert\Http\Client\Concerns\WhaleAlertClientInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
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
     * @param  Carbon  $from from timestamp
     * @param  Carbon|null  $to to timestamp
     * @param  int|null  $min min transaction value
     * @param  string|null  $currency specific cryptocurrency to return,
     * all currencies are returned otherwise
     *
     * @throws WhaleAlertRequestException
     */
    public function transactions(Carbon $from, Carbon $to = null, int $min = null, string $currency = null): Response
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

        $supportedCurrencies = collect(config('whale-alert.supported_currencies'));

        if (! empty($currency) && ! $supportedCurrencies->contains($currency)) {
            throw new InvalidArgumentException(vsprintf('Unsupported currency "%s" passed. Supported currencies are %s.', [
                $currency,
                $supportedCurrencies->implode(', '),
            ]));
        }

        return $this->client->transactions(
            from: $from,
            to: $to,
            min: $min,
            currency: $currency
        );
    }
}
