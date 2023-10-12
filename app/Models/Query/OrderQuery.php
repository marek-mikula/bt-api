<?php

namespace App\Models\Query;

use App\Models\Order;
use App\Models\Query\Traits\BelongsToCurrencyPair;
use App\Models\Query\Traits\BelongsToUser;
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
}
