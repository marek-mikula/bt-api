<?php

namespace Domain\Quiz\Http\Requests\Data;

use App\Data\BaseData;
use Illuminate\Support\Collection;

class FinishRequestData extends BaseData
{
    /**
     * @param  Collection<FinishRequestAnswerData>  $answers
     */
    public function __construct(
        public readonly Collection $answers,
    ) {
    }
}
