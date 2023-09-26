<?php

namespace Domain\Quiz\Http\Requests\Data;

use App\Data\BaseData;

class FinishRequestAnswerData extends BaseData
{
    public function __construct(
        public readonly int $id,
        public readonly int $answer,
    ) {
    }
}
