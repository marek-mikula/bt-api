<?php

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;

class QuizTakenException extends HttpException
{
    public function __construct()
    {
        parent::__construct(ResponseCodeEnum::QUIZ_TAKEN, 'Quiz was already completed.');
    }
}
