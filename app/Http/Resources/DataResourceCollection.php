<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DataResourceCollection extends ResourceCollection
{
    public $collects = DataResource::class;
}
