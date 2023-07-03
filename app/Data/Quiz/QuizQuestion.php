<?php

namespace App\Data\Quiz;

use Spatie\LaravelData\Data;

class QuizQuestion extends Data
{
    /**
     * @param QuizAnswer[] $answers
     */
    public function __construct(
        public readonly int $id,
        public readonly string $text,
        public readonly string $hint,
        public readonly array $answers,
    ) {
    }
}
