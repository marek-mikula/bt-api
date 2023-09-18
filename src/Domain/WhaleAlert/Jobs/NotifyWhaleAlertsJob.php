<?php

namespace Domain\WhaleAlert\Jobs;

use App\Enums\QueueEnum;
use App\Jobs\BaseJob;
use App\Models\Currency;
use App\Models\User;
use App\Models\WhaleAlert;
use Domain\WhaleAlert\Data\WhaleAlertGroupData;
use Domain\WhaleAlert\Notifications\WhaleAlertNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotifyWhaleAlertsJob extends BaseJob
{
    public function __construct()
    {
        $this->onQueue(QueueEnum::WHALE_ALERTS->value);
    }

    public function handle(): void
    {
        $users = User::query()
            ->where('whale_notification_enabled', '=', 1);

        if (! $users->exists()) {
            return;
        }

        $alerts = DB::table('whale_alerts')
            ->select([
                'currency_id',
                DB::raw('COUNT(id) as count'),
                DB::raw('SUM(amount) as amount'),
                DB::raw('SUM(amount_usd) as amount_usd'),
            ])
            ->whereNull('notified_at')
            ->groupBy('currency_id');

        if (! $alerts->exists()) {
            return;
        }

        /** @var Collection<WhaleAlertGroupData> $data */
        $data = $alerts
            ->get()
            ->map(static function (object $item): WhaleAlertGroupData {
                /** @var Currency $currency */
                $currency = Currency::query()->findOrFail((int) $item->currency_id);

                return WhaleAlertGroupData::from([
                    'count' => (int) $item->count,
                    'amount' => floatval($item->amount),
                    'amountUsd' => floatval($item->amount_usd),
                    'currencySymbol' => $currency->symbol,
                    'currencyName' => $currency->name,
                ]);
            });

        $users->chunk(50, function (Collection $users) use ($data): void {
            foreach ($data as $item) {
                $notification = new WhaleAlertNotification($item);

                /** @var User $user */
                foreach ($users as $user) {
                    $user->notify($notification);
                }
            }
        });

        // update notified_at timestamp

        WhaleAlert::query()
            ->whereNull('notified_at')
            ->update([
                'notified_at' => now(),
            ]);
    }
}
