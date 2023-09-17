<?php

namespace Domain\WhaleAlert\Http\Client;

use App\Traits\MocksData;
use Carbon\Carbon;
use Domain\WhaleAlert\Http\Client\Concerns\WhaleAlertClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class WhaleAlertClientMock implements WhaleAlertClientInterface
{
    use MocksData;

    public function status(): Response
    {
        return response_from_client(data: $this->mockData('WhaleAlert', 'status.json'));
    }

    public function transactions(Carbon $from, ?Carbon $to = null, ?int $min = null, ?Collection $currencies = null): Response
    {
        return response_from_client(data: $this->mockData('WhaleAlert', 'transactions.json'));
    }
}
