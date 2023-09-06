<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssetResourceCollection extends ResourceCollection
{
    public $collects = AssetResource::class;
}
