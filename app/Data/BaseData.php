<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BaseData extends Data
{
    public function toResource(): array
    {
        return $this->toArray();
    }
}
