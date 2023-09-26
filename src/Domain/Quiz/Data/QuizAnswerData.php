<?php

namespace Domain\Quiz\Data;

use App\Data\BaseData;

class QuizAnswerData extends BaseData
{
    public function __construct(
        public readonly int $id,
        public readonly string $text,
        public readonly bool $correct = false,
    ) {
    }
}
