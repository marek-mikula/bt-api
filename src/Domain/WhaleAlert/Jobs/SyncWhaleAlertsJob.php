<?php

namespace Domain\WhaleAlert\Jobs;

use Apis\WhaleAlert\Http\WhaleAlertApi;
use App\Enums\QueueEnum;
use App\Jobs\BaseBatchJob;
use App\Models\Currency;
use App\Models\WhaleAlert;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SyncWhaleAlertsJob extends BaseBatchJob
{
    public function __construct(private readonly string $currency)
    {
        $this->onQueue(QueueEnum::WHALE_ALERTS->value);
    }

    public function handle(WhaleAlertApi $api): void
    {
        $minValue = 1_000_000;

        $seconds = (now()->minute * 60) + now()->second;

        // calculate the from and to timestamps
        // for previous half hour

        if ($seconds > (30 * 60)) {
            // second half of an hour

            $from = now()
                ->startOfHour();
            $to = now()
                ->startOfHour()
                ->minutes(30);
        } else {
            // first half of an hour

            $from = now()
                ->startOfHour()
                ->subHour()
                ->minutes(30);
            $to = now()
                ->startOfHour();
        }

        $response = $api->transactions(
            from: $from,
            to: $to,
            min: $minValue,
            currency: $this->currency
        );

        /** @var Collection<array> $transactions */
        $transactions = $response
            // collect transactions data
            // from response
            ->collect('transactions')

            // take only single transactions
            ->filter(static fn (array $transaction): bool => ((int) $transaction['transaction_count']) === 1)

            // filter out transaction with less
            // value, because the API does not
            // work sometimes
            ->filter(static fn (array $transaction): bool => ((int) $transaction['amount_usd']) >= $minValue)

            // clear sender/receiver names and addresses
            ->map(static function (array $transaction): array {
                if (Arr::get($transaction, 'from.owner') === 'unknown') {
                    Arr::set($transaction, 'from.owner', null);
                }

                if (Arr::get($transaction, 'from.address') === 'Multiple Addresses') {
                    Arr::set($transaction, 'from.address', 'multiple');
                }

                if (Arr::get($transaction, 'to.owner') === 'unknown') {
                    Arr::set($transaction, 'to.owner', null);
                }

                if (Arr::get($transaction, 'to.address') === 'Multiple Addresses') {
                    Arr::set($transaction, 'to.address', 'multiple');
                }

                return $transaction;
            });

        // no whale alerts
        if ($transactions->isEmpty()) {
            return;
        }

        /** @var Currency $model */
        $model = Currency::query()
            ->where('symbol', '=', Str::upper($this->currency))
            ->firstOrFail();

        /** @var array $transaction */
        foreach ($transactions as $transaction) {
            WhaleAlert::query()->create([
                'currency_id' => $model->id,
                'hash' => (string) $transaction['hash'],
                'amount' => floatval($transaction['amount']),
                'amount_usd' => floatval($transaction['amount_usd']),
                'sender_address' => empty($transaction['from']['address']) ? null : (string) $transaction['from']['address'],
                'sender_name' => empty($transaction['from']['owner']) ? null : (string) $transaction['from']['owner'],
                'receiver_address' => empty($transaction['to']['address']) ? null : (string) $transaction['to']['address'],
                'receiver_name' => empty($transaction['to']['owner']) ? null : (string) $transaction['to']['owner'],
            ]);
        }
    }
}
