<?php

namespace App\Models\Query;

use App\Models\Order;
use App\Models\Query\Traits\BelongsToCurrencyPair;
use App\Models\Query\Traits\BelongsToUser;
use Domain\Cryptocurrency\Enums\OrderStatusEnum;
use Domain\Cryptocurrency\Enums\OrderTypeEnum;

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
        return $this->where('type', '=', OrderTypeEnum::BUY->name);
    }

    public function sell(): self
    {
        return $this->where('type', '=', OrderTypeEnum::SELL->name);
    }
}
