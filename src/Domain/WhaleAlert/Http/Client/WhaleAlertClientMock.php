<?php

namespace Domain\WhaleAlert\Http\Client;

use App\Traits\MocksData;
use Carbon\Carbon;
use Domain\WhaleAlert\Http\Client\Concerns\WhaleAlertClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class WhaleAlertClientMock implements WhaleAlertClientInterface
{
    use MocksData;

    public function status(): Response
    {
        return response_from_client(data: $this->mockData('WhaleAlert', 'status.json'));
    }

    public function transactions(Carbon $from, Carbon $to = null, int $min = null, string $currency = null): Response
    {
        if (! empty($currency)) {
            return response_from_client(data: $this->mockData('WhaleAlert', vsprintf('transactions/%s.json', [
                Str::lower($currency),
            ])));
        }

        // first load empty json
        $data = $this->mockData('WhaleAlert', 'transactions/empty.json');

        // load all supported currencies one by one
        foreach (config('whale-alert.supported_currencies') as $currency) {
            $currencyData = $this->mockData('WhaleAlert', vsprintf('transactions/%s.json', [
                Str::lower($currency),
            ]));

            if (empty($currencyData['transactions'])) {
                continue;
            }

            $data['transactions'] = array_merge(array_values($data['transactions']), array_values($currencyData['transactions']));
        }

        // reset keys
        $data['transactions'] = array_values($data['transactions']);

        // set correct count
        $data['count'] = count($data['transactions']);

        return response_from_client(data: $data);
    }
}
