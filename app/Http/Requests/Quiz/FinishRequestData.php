<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class FinishRequestData extends Data
{
    /**
     * @param Collection<FinishRequestAnswerData> $answers
     */
    public function __construct(
        public readonly Collection $answers,
    ) {
    }
}
