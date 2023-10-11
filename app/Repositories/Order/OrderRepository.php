<?php

namespace App\Repositories\Order;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        /** @var Order $order */
        $order = Order::query()->create($data);

        return $order;
    }
}
