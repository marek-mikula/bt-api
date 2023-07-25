<?php

namespace App\Http\Resources;

class NotificationPaginatedResourceCollection extends PaginatedResourceCollection
{
    public $collects = NotificationResource::class;
}
