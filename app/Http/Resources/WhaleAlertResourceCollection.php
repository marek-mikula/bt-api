<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WhaleAlertResourceCollection extends ResourceCollection
{
    public $collects = WhaleAlertResource::class;
}
