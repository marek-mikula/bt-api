<?php

namespace App\Http\Resources;

use App\Models\Limits;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Limits $resource
 */
class LimitsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'userId' => $this->resource->user_id,
            'trade' => [
                'enabled' => $this->resource->trade_enabled,
                'daily' => $this->resource->trade_daily,
                'weekly' => $this->resource->trade_weekly,
                'monthly' => $this->resource->trade_monthly,
            ],
            'cryptocurrency' => [
                'enabled' => $this->resource->cryptocurrency_enabled,
                'period' => $this->resource->cryptocurrency_period?->value,
                'min' => $this->resource->cryptocurrency_min,
                'max' => $this->resource->cryptocurrency_max,
            ],
            'marketCap' => [
                'enabled' => $this->resource->market_cap_enabled,
                'period' => $this->resource->market_cap_period?->value,
                'margin' => $this->resource->market_cap_margin,
                'micro' => [
                    'enabled' => $this->resource->market_cap_micro_enabled,
                    'value' => $this->resource->market_cap_micro,
                ],
                'small' => [
                    'enabled' => $this->resource->market_cap_small_enabled,
                    'value' => $this->resource->market_cap_small,
                ],
                'mid' => [
                    'enabled' => $this->resource->market_cap_mid_enabled,
                    'value' => $this->resource->market_cap_mid,
                ],
                'large' => [
                    'enabled' => $this->resource->market_cap_large_enabled,
                    'value' => $this->resource->market_cap_large,
                ],
                'mega' => [
                    'enabled' => $this->resource->market_cap_mega_enabled,
                    'value' => $this->resource->market_cap_mega,
                ],
            ],
        ];
    }
}
