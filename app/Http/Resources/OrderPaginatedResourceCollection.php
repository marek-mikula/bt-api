<?php

namespace App\Http\Resources;

class OrderPaginatedResourceCollection extends PaginatedResourceCollection
{
    public $collects = OrderResource::class;
}
