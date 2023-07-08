<?php

namespace App\Data\Quiz;

use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class QuizQuestionData extends Data
{
    /**
     * @param  QuizAnswerData[]  $answers
     */
    public function __construct(
        public readonly int $id,
        public readonly string $text,
        public readonly string $hint,
        public readonly array $answers,
    ) {
    }

    public function getCorrectAnswer(): QuizAnswerData
    {
        return Arr::first($this->answers, fn (QuizAnswerData $answer): bool => $answer->correct);
    }
}
