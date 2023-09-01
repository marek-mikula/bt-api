<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AlertResourceCollection extends ResourceCollection
{
    public $collects = AlertResource::class;
}
