<?php

namespace App\Models\Query;

use App\Models\Order;
use App\Models\Query\Traits\BelongsToCurrencyPair;
use App\Models\Query\Traits\BelongsToUser;
use Domain\Cryptocurrency\Enums\OrderSideEnum;
use Domain\Cryptocurrency\Enums\OrderStatusEnum;

/**
 * @see Order
 */
class OrderQuery extends BaseQuery
{
    use BelongsToUser;
    use BelongsToCurrencyPair;

    public function waiting(): self
    {
        return $this->whereIn('status', [
            OrderStatusEnum::NEW->name,
            OrderStatusEnum::PARTIALLY_FILLED->name,
            OrderStatusEnum::PENDING_CANCEL->name,
        ]);
    }

    public function buy(): self
    {
        return $this->where('side', '=', OrderSideEnum::BUY->name);
    }

    public function sell(): self
    {
        return $this->where('side', '=', OrderSideEnum::SELL->name);
    }

    public function ofBinanceUuid(string $uuid): self
    {
        return $this->where('binance_uuid', '=', $uuid);
    }

    public function ofBinanceId(int $id): self
    {
        return $this->where('binance_id', '=', $id);
    }
}
