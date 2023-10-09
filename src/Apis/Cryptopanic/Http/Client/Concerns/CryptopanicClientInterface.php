<?php

namespace Apis\Cryptopanic\Http\Client\Concerns;

use Apis\Cryptopanic\Exceptions\CryptopanicRequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

interface CryptopanicClientInterface
{
    /**
     * Retrieves the latest news from API, if $currencies
     * parameter is passed, the news are filtered
     * by those currencies.
     *
     * Maximum number of currencies if 50.
     *
     * @param  Collection<string>|null  $currencies
     *
     * @throws CryptopanicRequestException
     */
    public function latestNews(Collection $currencies = null): Response;
}
