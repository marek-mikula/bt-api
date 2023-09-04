<?php

namespace Domain\User\Services;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Models\Limits;
use App\Models\User;
use App\Repositories\Limits\LimitsRepositoryInterface;
use Domain\User\Http\Requests\Data\UpdateLimitsRequestData;

class UserLimitsSettingsService
{
    public function __construct(
        private readonly LimitsRepositoryInterface $limitsRepository,
    ) {
    }

    public function show(User $user): Limits
    {
        /** @var Limits|null $limits */
        $limits = $user->limits()->first();

        if (! $limits) {
            $limits = $this->limitsRepository->create([
                'user_id' => $user->id,
            ]);
        }

        return $limits;
    }

    public function update(User $user, UpdateLimitsRequestData $data): Limits
    {
        /** @var Limits|null $limits */
        $limits = $user->limits()->first();

        if (! $limits) {
            $limits = $this->limitsRepository->create([
                'user_id' => $user->id,
            ]);
        }

        // limits can be updated only once in couple days
        if (! $limits->canBeUpdated()) {
            throw new HttpException(responseCode: ResponseCodeEnum::LIMITS_LOCKED, data: [
                'resetAt' => $limits->getResetTime()->toIso8601String(),
            ]);
        }

        $update = [
            // trade
            'trade_enabled' => $data->tradeEnabled,
            'trade_daily' => $data->tradeEnabled ? $data->tradeDaily : null,
            'trade_weekly' => $data->tradeEnabled ? $data->tradeWeekly : null,
            'trade_monthly' => $data->tradeEnabled ? $data->tradeMonthly : null,

            // cryptocurrency
            'cryptocurrency_enabled' => $data->cryptocurrencyEnabled,
            'cryptocurrency_min' => $data->cryptocurrencyEnabled ? $data->cryptocurrencyMin : null,
            'cryptocurrency_max' => $data->cryptocurrencyEnabled ? $data->cryptocurrencyMax : null,

            // market cap
            'market_cap_enabled' => $data->marketCapEnabled,
            'market_cap_margin' => $data->marketCapEnabled ? $data->marketCapMargin : null,

            'market_cap_micro_enabled' => $data->marketCapEnabled && $data->marketCapMicroEnabled,
            'market_cap_micro' => $data->marketCapEnabled && $data->marketCapMicroEnabled ? $data->marketCapMicro : null,

            'market_cap_small_enabled' => $data->marketCapEnabled && $data->marketCapSmallEnabled,
            'market_cap_small' => $data->marketCapEnabled && $data->marketCapSmallEnabled ? $data->marketCapSmall : null,

            'market_cap_mid_enabled' => $data->marketCapEnabled && $data->marketCapMidEnabled,
            'market_cap_mid' => $data->marketCapEnabled && $data->marketCapMidEnabled ? $data->marketCapMid : null,

            'market_cap_large_enabled' => $data->marketCapEnabled && $data->marketCapLargeEnabled,
            'market_cap_large' => $data->marketCapEnabled && $data->marketCapLargeEnabled ? $data->marketCapLarge : null,

            'market_cap_mega_enabled' => $data->marketCapEnabled && $data->marketCapMegaEnabled,
            'market_cap_mega' => $data->marketCapEnabled && $data->marketCapMegaEnabled ? $data->marketCapMega : null,
        ];

        return $this->limitsRepository->update($limits, $update);
    }
}
