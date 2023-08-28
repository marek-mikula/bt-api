<?php

namespace Domain\Quiz\Http\Requests\Data;

use Spatie\LaravelData\Data;

class FinishRequestAnswerData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $answer,
    ) {
    }
}
