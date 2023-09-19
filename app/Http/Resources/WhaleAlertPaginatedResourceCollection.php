<?php

namespace App\Http\Resources;

class WhaleAlertPaginatedResourceCollection extends PaginatedResourceCollection
{
    public $collects = WhaleAlertResource::class;
}
