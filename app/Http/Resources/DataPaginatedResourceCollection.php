<?php

namespace App\Http\Resources;

class DataPaginatedResourceCollection extends PaginatedResourceCollection
{
    public $collects = DataResource::class;
}
