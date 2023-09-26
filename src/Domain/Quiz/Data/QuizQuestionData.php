<?php

namespace Domain\Quiz\Data;

use App\Data\BaseData;
use Illuminate\Support\Arr;

class QuizQuestionData extends BaseData
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
        return Arr::first($this->answers, static fn (QuizAnswerData $answer): bool => $answer->correct);
    }
}
