<?php

namespace App\Data\Quiz;

use Spatie\LaravelData\Data;

class QuizAnswerData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $text,
        public readonly bool $correct = false,
    ) {
    }
}