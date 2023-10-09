<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyResourceCollection extends ResourceCollection
{
    public $collects = CurrencyResource::class;
}
