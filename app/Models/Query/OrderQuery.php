<?php

namespace App\Models\Query;

use App\Models\Order;
use App\Models\Query\Traits\BelongsToCurrencyPair;
use App\Models\Query\Traits\BelongsToUser;
use Domain\Order\Enums\OrderSideEnum;
use Domain\Order\Enums\OrderStatusEnum;

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
            OrderStatusEnum::NEW->value,
            OrderStatusEnum::PARTIALLY_FILLED->value,
            OrderStatusEnum::PENDING_CANCEL->value,
        ]);
    }

    public function buy(): self
    {
        return $this->where('side', '=', OrderSideEnum::BUY->value);
    }

    public function sell(): self
    {
        return $this->where('side', '=', OrderSideEnum::SELL->value);
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
